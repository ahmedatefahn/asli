<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace'=> 'App\Http\Controllers\Api'],function () {
    Route::post('auth','ApiController@auth');
    Route::post('add-brand','ApiController@addBrand');
    Route::get('get-brands','ApiController@getBrands');
    Route::post('add-product','ApiController@addProduct');
    Route::get('get-products','ApiController@getProducts');
    Route::post('add-barcode','ApiController@addBarcode');
    Route::get('get-barcodes','ApiController@getBarcodes');
    Route::post('search-barcode','ApiController@searchBarcode');
    Route::Post('get-products-by-brand','ApiController@getProductsByBrand');

});



