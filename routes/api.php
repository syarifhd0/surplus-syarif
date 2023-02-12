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

    $api->group(['prefix' => '/category'], function ($api) {
        $api->get('/{categoryId}/product', 'CategoryController@getCategoryProduct');
        $api->post('/{categoryId}/product/{productId}', 'CategoryController@postCategoryProductById');
        $api->delete('/{categoryId}/product/{productId}', 'CategoryController@deleteCategoryProductById');
    });

    $api->group(['prefix' => '/product'], function ($api) {
        $api->get('/{productId}/category', 'ProductController@getCategoryProduct');
        $api->post('/{productId}/category/{categoryId}', 'ProductController@postCategoryProductById');
        $api->delete('/{productId}/category/{categoryId}', 'ProductController@deleteCategoryProductById');

        $api->get('/{productId}/image', 'ProductController@getProductImage');
        $api->post('/{productId}/image/{imageId}', 'ProductController@postProductImageById');
        $api->delete('/{productId}/image/{imageId}', 'ProductController@deleteProductImageById');
    });

    $api->group(['prefix' => '/image'], function ($api) {
        $api->get('/{imageId}/product', 'ImageController@getProductImage');
        $api->post('/{imageId}/product/{productId}', 'ImageController@postProductImageById');
        $api->delete('/{imageId}/product/{productId}', 'ImageController@deleteProductImageById');
    });
});
