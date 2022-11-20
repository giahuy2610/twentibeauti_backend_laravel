<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/homepage', function () {
//     return inertia('welcome');
// });

// Route::group(['prefix' => 'donvi'], function () {
//     //danhsach
//     Route::get('/', 'DonViController@getDSDonVi')->name('ds-donvi.get');
//     //them
//     Route::get('them', 'DonViController@getThemDonVi')->name('them-donvi.get');
//     Route::post('them', 'DonViController@postThemDonVi')->name('them-donvi.post');
//     //sua
//     Route::get('sua/{id}', 'DonViController@getSuaDonVi')->name('sua-donvi.get');
//     Route::post('sua/{id}', 'DonViController@postSuaDonVi')->name('sua-donvi.post');
//     //xoa
//     Route::get('xoa/{id}', 'DonViController@getXoaDonVi')->name('xoa-donvi.get');
//   });

Route::post('dangnhap', [CustomerController::class, 'getLogin'])->name('dangnhap.get');