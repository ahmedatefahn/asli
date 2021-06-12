<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('get-product', function () {
    return view('view-product');
});
Route::group(['namespace'=> 'App\Http\Controllers'],function () {
    Route::post('login','MainController@login');
    Route::get('index','MainController@index');
    Route::get('products','MainController@getBrands');
    Route::get('product/{id}','MainController@getAProduct');
    Route::get('add-product','MainController@getAddProduct');
    Route::post('add-product','MainController@addProduct');
    Route::get('add-brand','MainController@getAddBrand');
    Route::post('add-brand','MainController@addBrand');
    
});