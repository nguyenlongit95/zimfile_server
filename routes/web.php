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
    Route::get('/dashboard', [\App\Http\Controllers\AdminAPIController::class, 'dashboard']);
    Route::get('/logout', [\App\Http\Controllers\AdminAPIController::class, 'logout']);

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listCustomers']);
        Route::post('/search', [\App\Http\Controllers\AdminAPIController::class, 'searchCustomers']);
        Route::get('/edit/{id}', [\App\Http\Controllers\AdminAPIController::class, 'editCustomers']);
        Route::post('/update/{id}', [\App\Http\Controllers\AdminAPIController::class, 'updateCustomers']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminAPIController::class, 'deleteCustomers']);
    });

    Route::group(['prefix' => 'editors'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listEditors']);
        Route::post('/search', [\App\Http\Controllers\AdminAPIController::class, 'searchEditors']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminAPIController::class, 'updateEditors']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminAPIController::class, 'deleteEditor']);
        Route::get('/assign-job/{id}', [\App\Http\Controllers\AdminAPIController::class, 'assignJobs']);
    });

    Route::group(['prefix' => 'qc'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listQC']);
        Route::post('/search', [\App\Http\Controllers\AdminAPIController::class, 'searchQC']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminAPIController::class, 'editQC']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminAPIController::class, 'deleteQC']);
    });
});