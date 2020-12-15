<?php

use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TransactionController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::GET('user', [UserController::class, 'fetch']);
    Route::POST('user', [UserController::class, 'updateProfile']);
    Route::POST('user/photo', [UserController::class, 'updatePhoto']);
    Route::GET('transaction', [TransactionController::class, 'all']);
    Route::POST('transaction/{id}', [TransactionController::class, 'update']);
    Route::POST('checkout', [TransactionController::class, 'checkout']);
    Route::POST('logout', [UserController::class, 'logout']);
});

Route::POST('login', [UserController::class, 'login']);
Route::POST('register', [UserController::class, 'register']);

Route::GET('food', [FoodController::class, 'all']);

Route::POST('midtrans/callback', [MidtransController::class, 'midtransCallback']);
