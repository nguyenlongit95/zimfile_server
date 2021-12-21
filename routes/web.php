<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginAndRegisterController;
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

/*
 * Route Login
 * */
Route::get('/admin/login', [LoginAndRegisterController::class, 'getLogin']);
Route::post('/admin/login',[LoginAndRegisterController::class, 'postLogin']);
/*
 * Route cho admin
 * */
Route::group(['prefix'=>'admin'],function() {
    // Trang DashBoard sẽ là nơi thống kê sản phẩm và các thông tin liên quan
    Route::get('/dashboard', [\App\Http\Controllers\AdminAPIController::class, 'dashboard']);
    Route::get('/logout', [\App\Http\Controllers\AdminAPIController::class, 'logout']);
    Route::get('/export', [\App\Http\Controllers\AdminAPIController::class, 'exportCSV']);

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
        Route::get('/assign-group/{id}', [\App\Http\Controllers\AdminAPIController::class, 'editorAssignGroup']);
        Route::get('/{editorId}/assign-group/{groupId}', [\App\Http\Controllers\AdminAPIController::class, 'assignGroupForEditor']);
        Route::get('/{editorId}/remove-group/{groupId}', [\App\Http\Controllers\AdminAPIController::class, 'removeGroupForEditor']);
    });

    Route::group(['prefix' => 'qc'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listQC']);
        Route::post('/search', [\App\Http\Controllers\AdminAPIController::class, 'searchQC']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminAPIController::class, 'editQC']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminAPIController::class, 'deleteQC']);
        Route::get('/assign-user/{id}', [\App\Http\Controllers\AdminAPIController::class, 'assignUsers']);
        Route::get('/remove-belong/{id}', [\App\Http\Controllers\AdminAPIController::class, 'removeBelong']);
        Route::post('/assign-user/{id}', [\App\Http\Controllers\AdminAPIController::class, 'assigningUsers']);
    });

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listJobsDashBoard']);
        Route::get('/search', [\App\Http\Controllers\AdminAPIController::class, 'searchJobsDashBoard']);
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listGroups']);
        Route::post('/create', [\App\Http\Controllers\AdminAPIController::class, 'createGroup']);
        Route::post('{id}/edit', [\App\Http\Controllers\AdminAPIController::class, 'update']);
        Route::get('{id}/assign-customers', [\App\Http\Controllers\AdminAPIController::class, 'assignCustomer']);
        Route::get('/{groupId}/assign-customer/{customerId}', [\App\Http\Controllers\AdminAPIController::class, 'assignCustomerToGroup']);
        Route::get('/{groupId}/remove-customer/{customerId}', [\App\Http\Controllers\AdminAPIController::class, 'removeCustomerInGroup']);
    });

    Route::group(['prefix' => 'sub-admin'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listSubAdmin']);
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [\App\Http\Controllers\AdminAPIController::class, 'listNotifications']);
        Route::post('/create', [\App\Http\Controllers\AdminAPIController::class, 'createNotification']);
        Route::post('/edit/{id}', [\App\Http\Controllers\AdminAPIController::class, 'editNotification']);
        Route::get('/delete/{id}', [\App\Http\Controllers\AdminAPIController::class, 'deleteNotification']);
    });
});
