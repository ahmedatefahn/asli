<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function addBarcodes(Request $request)
    {
        $message = "You cannot add empty code";

        if (!$request->barcodes) {
            return redirect()->back()->with(['message' => $message, 'error' => true]);
        }

        $error = false;
        $codes = explode(',', $request->barcodes);
        $existedCodes = $this->_areBarcodesExisted($codes);

        if ($existedCodes['count']) {
            $message = 'Error codes duplicated:  `' . implode(', ', $existedCodes['codes']) . '`';
            $codes = $this->_filterNewBarcodes($codes, $existedCodes['codes']);
            $error = true;
        }

        $data = [];
        foreach ($codes as $code) {
            $data[] = [
                'code' => $code,
                'product_id' => $request->product_id,
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
     * @param $barcodes
     * @return mixed
     */
    private function _areBarcodesExisted($barcodes)
    {
        $query = Barcode::whereIn('code', $barcodes);
        $count = $query->count();
        $existedBarcodes = $query->pluck('code')->toArray();

        return [
            'count' => $count,
            'codes' => $existedBarcodes
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
}
