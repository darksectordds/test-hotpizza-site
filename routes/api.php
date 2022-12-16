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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'product', 'as' => 'product.'], function(){
    Route::get('/', 'App\Http\Controllers\Api\ProductController@index');
});

Route::group(['prefix' => 'cart', 'as' => 'cart.'], function(){
    Route::group(['prefix' => '{product_id}', 'as' => 'product_id.'], function(){
        Route::post('/', 'App\Http\Controllers\Api\CartProductController@create')->name('push');
    });
});
