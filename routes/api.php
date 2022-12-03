<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductCollectionController;
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

Route::group(['prefix' => 'coupon'], function () {
    // add a new coupon
    Route::post('create', [CouponController::class, 'create'])->name('coupon.create');

    // update coupon 
    Route::post('update', [CouponController::class, 'update'])->name('coupon.update');

    // remove a coupon
    Route::post('destroy', [CouponController::class, 'destroy'])->name('coupon.destroy');

    // get a specific coupons in database
    Route::get('show/{id}', [CouponController::class, 'show'])->name('coupon.show');

    //get all coupons in database
    Route::post('index', [CouponController::class, 'index'])->name('coupon.index');

    //get all coupons in database which are available
    Route::get('available', [CouponController::class, 'indexAvailable'])->name('coupon.available');
});
Route::group(['prefix' => 'collection'], function () {
    
    // get a specific coupons in database
    Route::get('show/{id?}', [CollectionController::class, 'show'])->name('collection.show');
    //insert in database
    Route::post('create', [CollectionController::class, 'create'])->name('collection.create');
    //update db
    Route::put('update/{id}', [CollectionController::class, 'update'])->name('collection.update');
    //delete db
    Route::delete('delete/{id}', [CollectionController::class, 'delete'])->name('collection.delete');
});
Route::group(['prefix' => 'productcollection'], function () {
    
    // get a specific coupons in database
    Route::get('show/{id?}', [ProductCollectionController::class, 'show'])->name('productcollection.show');
});
Route::group(['prefix' => 'address'], function () {
    
    // get a specific products in database
    Route::get('read/{id}', [AddressController::class, 'read'])->name('address.read');


    //get all products in database
    Route::post('index', [ProductController::class, 'index'])->name('product.index');
});
//
Route::group(['prefix' => 'product'], function () {
    // add a new product
    Route::post('create', [ProductController::class, 'create'])->name('product.create');

    // update product 
    Route::post('update', [ProductController::class, 'update'])->name('product.update');

    // remove a product
    Route::post('destroy', [ProductController::class, 'destroy'])->name('product.destroy');

    // get a specific products in database
    Route::get('show/{id}', [ProductController::class, 'show'])->name('product.show');

    Route::get('read/{id}', [ProductController::class, 'read'])->name('product.read');

    //get all products in database
    Route::post('index', [ProductController::class, 'index'])->name('product.index');
});

Route::group(['prefix' => 'cart'], function () {
    // add a new product in cart
    // Route::post('create', [CartController::class, 'create'])->name('cart.create');

    // change quantity to product quantity in cart 
    Route::post('update', [CartController::class, 'update'])->name('cart.update');

    // remove a product from cart
    Route::post('destroy', [CartController::class, 'destroy'])->name('cart.destroy');

    // get all products in cart 
    Route::post('show', [CartController::class, 'show'])->name('cart.show');
});

Route::post('login', [CustomerController::class, 'getLogin'])->name('login.get');
