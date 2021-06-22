<?php

namespace App\Http\Controllers;

use App\Imports\BarcodesImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use Redirect;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Barcode;
use Session;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],

        ]);

        if (Auth::attempt($credentials)) {
            $products = Product::with('Brand')->get();
            $brands = Brand::with('products')->get();
            if ($products) {
                return view('all-products', ['products' => $products, 'brands' => $brands]);
            }


        } else {
            Session::flash('message', "Please check your name or password again!!");
            return Redirect::back();

        }

    }

    public function getProducts()
    {
        $products = Product::with('Brand')->get();
        if ($products) {
            return view('all-products', ['products' => $products]);
        }
    }

    public function getBrands()
    {
        $brands = Brand::with('products')->get();

        if ($brands) {
            return view('all-products', ['brands' => $brands]);
        }
    }

    public function getAProduct($id)
    {

        $product = Product::where('id', $id)->with(['Barcodes' => function ($query) {
            $query->orderBy('created_at', 'desc')->get();
        }, 'Brand'])->first();

        if ($product) {
            return view('view-product', ['product' => $product]);
        }
    }

    public function getAddProduct()
    {
        $brands = Brand::get();
        return view('add-product', ['brands' => $brands]);
    }

    public function addProduct(Request $request)
    {
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {

            $file = $request->file("photo");
            $filename = Str::random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/ProductPhotos';
            $file->move($path, $filename);
            $data['photo'] = $path . '/' . $filename;
        }
        // dd($data);
        $product = Product::create($data);
        Session::flash('message', "Added Product Successfully");
        return Redirect::back();
    }

    public function getAddBrand()
    {

        return view('add-brand');
    }

    public function addBrand(Request $request)
    {
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {

            $file = $request->file("photo");
            $filename = Str::random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/BrandPhotos';
            $file->move($path, $filename);
            $data['photo'] = $path . '/' . $filename;
        }
        // dd($data);
        $brand = Brand::create($data);
        Session::flash('message', "Added Brand Successfully");
        return Redirect::back();
    }

    public function deleteBarcode($id)
    {
        $barcode = Barcode::findOrFail($id)->delete();
        Session::flash('message', "Delete Barcode Successfully");

        return Redirect::back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addBarcodes(Request $request)
    {
        $message = "You cannot add empty code";

        if (!$request->barcodes) {
            return redirect()->back()->with(['message' => $message, 'error' => true]);
        }

        $error = false;
        $secretCodes = explode(',', $request->barcodes);
        $existedSecretCodes = $this->_existedBarcodes($secretCodes);

        if ($existedSecretCodes['secret_codes_count']) {
            $message = 'Error codes duplicated:  `' . implode(', ', $existedSecretCodes['secret_codes']) . '`';
            $secretCodes = $this->_filterNewBarcodes($secretCodes, $existedSecretCodes['secret_codes']);
            $error = true;
        }

        $data = [];

        foreach ($secretCodes as $code) {
            $data[] = [
                'secret_code' => $code,
                'public_code' => '',
                'product_id' => $request->product_id,
                'custom_creation_date' => Carbon::now()->format('Y-m-d'),
                'scan_before' => 0,
            ];
        }

        if (!$error) {
            $message = "Barcodes have been added Successfully";
        }

        Barcode::insert($data);

        return redirect()->back()->with(['message' => $message, 'error' => $error]);
    }

    /**
     * @param $secretCodes
     * @param $publicCodes
     * @return mixed
     */
    private function _existedBarcodes($secretCodes, $publicCodes = [])
    {
        $publicCodes = Barcode::whereIn('public_code', $publicCodes);
        $secretCodes = Barcode::whereIn('secret_code', $secretCodes);


        $publicCodesCount = $publicCodes->count();
        $secretCodesCount = $secretCodes->count();

        $existedPublicCodes = $secretCodes->pluck('public_code')->toArray();
        $existedSecretCodes = $secretCodes->pluck('secret_code')->toArray();

        return [
            'public_codes_count' => $publicCodesCount,
            'secret_codes_count' => $secretCodesCount,
            'public_codes' => $existedPublicCodes,
            'secret_codes' => $existedSecretCodes
        ];
    }

    /**
     * @param $originalCodes
     * @param $existedCodes
     * @return array
     */
    private function _filterNewBarcodes($originalCodes, $existedCodes)
    {
        return array_diff($originalCodes, $existedCodes);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function importBarcodes(Request $request)
    {
        validator($request->all(), [
            'product_id' => 'required|exists:products,id',
            'codes_file' => 'required|mime:xlsx, csv, doc,docx,ppt,pptx,ods,odt,odp'
        ]);

        $rows = Excel::toArray(null, $request->file('codes_file'))[0]; // access first index
        $secretCodes = [];
        $publicCodes = [];
        $rowsCount = count($rows) - 1; // skip first row

        foreach ($rows as $key => $row) {
            if (!$key) continue; // skip first row in sheet.
            $secretCodes[$row[0]] = [
                'public_code' => $row[1],
                'date' => Carbon::parse($row[2])->format('Y-m-d')
            ];
            $publicCodes[] = $row[1];
        }

        $existedBarcodes = $this->_existedBarcodes(array_keys($secretCodes), $publicCodes);

        $message = $this->_getImportErrorMessage($existedBarcodes, $rowsCount);

        $filteredSecretCodes = $this->_filterNewBarcodes(array_keys($secretCodes), $existedBarcodes['secret_codes']);

        $toBeInsertedBarcodes = [];
        foreach ($filteredSecretCodes as $secretCode) {
            $codeData = $secretCodes[$secretCode];
            if (isset($codeData['public_code'])
                && isset($codeData['date'])
                && !in_array($codeData['public_code'], $existedBarcodes['public_codes'])) {
                $toBeInsertedBarcodes [] = [
                    'product_id' => $request->product_id,
                    'secret_code' => $secretCode,
                    'public_code' => $codeData['public_code'],
                    'custom_creation_date' => $codeData['date']
                ];
            }
        }

        Barcode::insert($toBeInsertedBarcodes);

        if (!$message) {
            $message = 'Data has been imported successfully';
        }

        return redirect()->back()->with(['import_results' => $message]);
    }

    /**
     * @param $existedBarcodes
     * @param $rowsCount
     * @return string
     */
    private function _getImportErrorMessage($existedBarcodes, $rowsCount)
    {
        $message = '';

        if ($existedBarcodes['public_codes_count']) {
            $message .= "There are : " . $existedBarcodes['public_codes_count'] . ' public codes duplicated <br>';
            $message .= '`' . implode(", ", $existedBarcodes['public_codes']) . '`<br>';
        }

        if ($existedBarcodes['secret_codes_count']) {
            $message .= "There are : " . $existedBarcodes['public_codes_count'] . ' secret codes duplicated <br>';
            $message .= '`' . implode(", ", $existedBarcodes['secret_codes']) . "`<br> Out Of $rowsCount rows";
        }

        return $message;
    }
}
