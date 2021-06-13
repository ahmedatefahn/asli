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
            if($products)
            {
                return view('all-products',['products'=>$products,'brands'=>$brands]);
            }
             
            
        }else{
            Session::flash('message', "Please check your name or password again!!");
            return Redirect::back();
            
        }

    }
    public function getProducts()
    {
        $products = Product::with('Brand')->get();
        if($products)
        {
            return view('all-products',['products'=>$products]);
        }
    }
    public function getBrands()
    {
        $brands = Brand::with('products')->get();
        
        if($brands)
        {
            return view('all-products',['brands'=>$brands]);
        }
    }
    public function getAProduct($id)
    {
         
        $product = Product::where('id',$id)->with(['Barcodes' => function($query){
            $query->orderBy('created_at', 'desc')->get();
        } , 'Brand'])->first();
        
        if($product)
        {
            return view('view-product',['product'=>$product]);
        }
    }
    public function getAddProduct()
    {
        $brands = Brand::get();
        return view('add-product',['brands'=>$brands]);
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
        $barcode = Barcode::where('id',$id)->first();
        if($barcode){
            $barcode->delete();
            Session::flash('message', "Delete Barcode Successfully");
            return Redirect::back();
        }
    }

    public function addBarcodes(Request $request)
    {
        // dd($request->product_id);
        $codes =  explode(',', $request->barcodes);
        foreach($codes as $code)
        {
            $barcode = new Barcode();
            $barcode->code = $code;
            $barcode->product_id = $request->product_id;
            $barcode->scan_before = 0;
            $barcode->save();
 
        }
        Session::flash('message', "Addes Barcodes Successfully");
        return Redirect::back();
    }

}
