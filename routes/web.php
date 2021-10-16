<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginAndRegisterController;
use \App\Http\Controllers\AdminController;
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
    return view('welcome');
});
/*
 * Route Login
 * */
Route::get('/admin/login', [LoginAndRegisterController::class, 'getLogin']);
Route::post('/admin/login',[LoginAndRegisterController::class, 'postLogin']);
/*
 * Route cho phia admin
 * */
Route::group(['prefix'=>'admin'],function() {
    // Trang DashBoard sẽ là nơi thống kê sản phẩm và các thông tin liên quan
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
    Route::get('/logout', [\App\Http\Controllers\AdminController::class, 'logout']);

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'listCustomers']);
        Route::post('/search', [\App\Http\Controllers\AdminController::class, 'searchCustomers']);
        Route::get('/edit/{id}', [\App\Http\Controllers\AdminController::class, 'editCustomers']);
        Route::post('/update/{id}', [\App\Http\Controllers\AdminController::class, 'updateCustomers']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminController::class, 'deleteCustomers']);
    });

    Route::group(['prefix' => 'editors'], function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'listEditors']);
        Route::post('/search', [\App\Http\Controllers\AdminController::class, 'searchEditors']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminController::class, 'updateEditors']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminController::class, 'deleteEditor']);
        Route::get('/assign-job/{id}', [\App\Http\Controllers\AdminController::class, 'assignJobs']);
    });

    Route::group(['prefix' => 'qc'], function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'listQC']);
        Route::post('/search', [\App\Http\Controllers\AdminController::class, 'searchQC']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminController::class, 'editQC']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminController::class, 'deleteQC']);
    });
});