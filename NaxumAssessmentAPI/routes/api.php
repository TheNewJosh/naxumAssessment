<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserAccountController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//real user application Apis
Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {
    Route::post('auth/register', [RegisterController::class, 'create']);
    Route::post('auth/login', [LoginController::class, 'login']);
    Route::post('auth/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('auth/user_list', [UserAccountController::class, 'list'])->middleware('auth:sanctum');
    Route::post('auth/user_search', [UserAccountController::class, 'search'])->middleware('auth:sanctum');
    Route::post('auth/updateProfile', [UserAccountController::class, 'updateProfile'])->middleware('auth:sanctum');

});
