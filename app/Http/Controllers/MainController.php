<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use Redirect;
use App\Models\Product;
use App\Models\Brand;
use Session;
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
            
            return redirect('index');
            
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
         
        $product = Product::where('id',$id)->with(['Brand','Barcodes'])->first();
        
        if($product)
        {
            return view('view-product',['product'=>$product]);
        }
    }
    

}
