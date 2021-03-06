<?php

use Illuminate\Support\Facades\Route;

/**
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/test-read-file', [\App\Http\Controllers\NASController::class, 'readFile']);
Route::get('/test', [\App\Http\Controllers\UserAPIController::class, 'index']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/admin/login', [\App\Http\Controllers\AuthController::class, 'adminLogin']);
Route::get('/download', [\App\Http\Controllers\UserAPIController::class, 'downloadFileProduct']);
Route::get('/time-server', [\App\Http\Controllers\UserAPIController::class, 'timeServer']);
Route::get('/create-dir-editor', [\App\Http\Controllers\UserAPIController::class, 'createDirEditor']);

/**
|
|--------------------------------------------------------------------------
|   Group router api auth
|--------------------------------------------------------------------------
|
*/
Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/test-connect-nas', [\App\Http\Controllers\UserAPIController::class, 'connectNAS']);
    /**
     * Route for Users
     */
    Route::post('/create-director', [\App\Http\Controllers\UserAPIController::class, 'createDir']);
    Route::post('/create-jobs', [\App\Http\Controllers\UserAPIController::class, 'createJobs']);
    Route::post('/upload-multi-file', [\App\Http\Controllers\UserAPIController::class, 'uploadMultiFile']);
    Route::get('/list-directories', [\App\Http\Controllers\UserAPIController::class, 'listDirectories']);
    Route::get('/list-my-jobs', [\App\Http\Controllers\UserAPIController::class, 'listMyJobs']);
    Route::get('/delete-file', [\App\Http\Controllers\UserAPIController::class, 'deleteFile']);
    Route::get('/delete-multi-file', [\App\Http\Controllers\UserAPIController::class, 'deleteMultipleFile']);
    Route::post('/change-password', [\App\Http\Controllers\UserAPIController::class, 'changePassword']);
    Route::get('/jobs-in-dir', [\App\Http\Controllers\UserAPIController::class, 'listJobInDir']);
    Route::get('/list-dir-in-done', [\App\Http\Controllers\UserAPIController::class, 'listDirInDone']);
    /**
     * Group route for admin panel
     */
    Route::group(['prefix' => 'admin'], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('/list', [\App\Http\Controllers\AdminAPIController::class, 'listUser']);
            Route::get('/detail', [\App\Http\Controllers\AdminAPIController::class, 'userDetail']);
            Route::get('/list-image', [\App\Http\Controllers\AdminAPIController::class, 'listImage']);
            Route::post('/update-profile', [\App\Http\Controllers\AdminAPIController::class, 'updateProfile']);
            Route::post('/create-user', [\App\Http\Controllers\AdminAPIController::class, 'createUser']);
            Route::post('/create-editor', [\App\Http\Controllers\AdminAPIController::class, 'createEditor']);
            Route::post('/create-qc', [\App\Http\Controllers\AdminAPIController::class, 'createQC']);
            Route::get('/delete-user', [\App\Http\Controllers\AdminAPIController::class, 'deleteUser']);
            Route::get('{date}/download-file', [\App\Http\Controllers\AdminAPIController::class, 'downloadFile']);
        });
        // Route jobs for admin panel
        Route::get('list-jobs', [\App\Http\Controllers\AdminAPIController::class, 'listJobs']);
        Route::get('manual-assign-job', [\App\Http\Controllers\AdminAPIController::class, 'manualAssignJob']);
        Route::get('export', [\App\Http\Controllers\AdminAPIController::class, 'exportCSV']);
    });
    /**
     * Group route for Editor
     */
    Route::group(['prefix' => 'editor'], function () {
        Route::get('list-jobs', [\App\Http\Controllers\EditorAPIController::class, 'listJobs']);
        Route::get('get-job', [\App\Http\Controllers\EditorAPIController::class, 'getJob']);
        Route::post('confirm-job', [\App\Http\Controllers\EditorAPIController::class, 'confirmJob']);
        Route::get('get-notifications', [\App\Http\Controllers\EditorAPIController::class, 'getNotifications']);
        Route::get('cancel-job', [\App\Http\Controllers\EditorAPIController::class, 'cancelJob']);
        Route::get('list-my-jobs', [\App\Http\Controllers\EditorAPIController::class, 'listMyJobs']);
    });
    /**
     * Group route for QC
     */
    Route::group(['prefix' => 'qc'], function () {
        Route::get('list-jobs', [\App\Http\Controllers\QCAPIController::class, 'listJobs']);
        Route::get('get-job-to-check', [\App\Http\Controllers\QCAPIController::class, 'getJobsToCheck']);
        Route::post('check-confirm-job', [\App\Http\Controllers\QCAPIController::class, 'checkConfirmJobs']);
    });
});
