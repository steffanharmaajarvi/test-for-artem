<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\LoyaltyPoints\Deposit\CancelLoyaltyPointsController;
use App\Http\Controllers\LoyaltyPoints\Deposit\DepositLoyaltyPointsController;
use App\Http\Controllers\LoyaltyPoints\Deposit\WithdrawLoyaltyPointsController;
use App\Http\Controllers\UserController;
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
Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user/logout', [UserController::class, 'logout']);

    // account management
    Route::post('account/create', [AccountController::class, 'create']);
    Route::post('account/activate/{type}/{id}', [AccountController::class, 'activate']);
    Route::post('account/deactivate/{type}/{id}', [AccountController::class, 'deactivate']);
    Route::get('account/balance/{type}/{id}', [AccountController::class, 'balance']);

    // loyalty points management
    Route::post('loyaltyPoints/deposit', [DepositLoyaltyPointsController::class, 'handle']);
    Route::post('loyaltyPoints/withdraw', [WithdrawLoyaltyPointsController::class, 'handle']);
    Route::post('loyaltyPoints/cancel', [CancelLoyaltyPointsController::class, 'handle']);
});





