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

Route::group(['middleware' => ['jsonify'], 'prefix' => 'v1'], function ($api) {
    $api->resource('category','CategoryController');
    $api->resource('product','ProductController');
    $api->resource('image','ImageController');
});
