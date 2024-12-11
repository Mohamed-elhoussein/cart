<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\GetGateController;
use App\Http\Controllers\transactionController;
use App\Http\Controllers\notificationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('user', [AuthController::class, 'me']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);



Route::resource('users', UserController::class);

Route::get('cars',         [CarController::class, 'index']);        // عرض جميع السيارات
Route::post('cars',        [CarController::class, 'store']);       // إنشاء سيارة جديدة
Route::get('cars/{id}',    [CarController::class, 'show']);    // عرض تفاصيل سيارة معينة
Route::put('cars/{id}',    [CarController::class, 'update']);  // تحديث السيارة
Route::delete('cars/{id}', [CarController::class, 'destroy']); // حذف السيارة



Route::get('wallet',   [WalletController::class, 'index']);
Route::post('wallet',            [WalletController::class, 'create']);
Route::get('wallet/{userId}',    [WalletController::class, 'show']);
Route::put('wallet/{userId}',    [WalletController::class, 'update']);
Route::delete('wallet/{userId}', [WalletController::class, 'destroy']);


// Route::get("gate",[GetGateController::class,"index"]);


Route::apiResource("transactions",transactionController::class);
Route::apiResource("gate",GateController::class);


Route::get("notification/{id}",[notificationController::class,"data"]);
