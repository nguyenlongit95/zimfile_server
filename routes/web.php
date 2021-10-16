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
    Route::get('/dashboard', [adminController::class, 'dashboard']);
    Route::get('/logout', [adminController::class, 'logout']);

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [adminController::class, 'listCustomers']);
        Route::post('/search', [adminController::class, 'searchCustomers']);
        Route::get('/edit/{id}', [adminController::class, 'editCustomers']);
        Route::post('/update/{id}', [adminController::class, 'updateCustomers']);
        Route::get('/delete/{id}', [adminController::class, 'deleteCustomers']);
    });

    Route::group(['prefix' => 'editors'], function () {
        Route::get('/', [adminController::class, 'listEditors']);
        Route::post('/search', [adminController::class, 'searchEditors']);
        Route::post('/edit/{id}', [adminController::class, 'updateEditors']);
        Route::get('/delete/{id}', [adminController::class, 'deleteEditor']);
        Route::get('/assign-job/{id}', [adminController::class, 'assignJobs']);
    });

    Route::group(['prefix' => 'qc'], function () {
        Route::get('/', [adminController::class, 'listQC']);
        Route::post('/search', [adminController::class, 'searchQC']);
        Route::post('/edit/{id}', [adminController::class, 'editQC']);
        Route::get('/delete/{id}', [adminController::class, 'deleteQC']);
    });
});