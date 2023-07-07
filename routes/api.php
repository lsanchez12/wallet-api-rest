<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

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
Route::get('/user/{id}', [UserController::class, 'getUser']);
Route::post('/user', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/wallet/{userId}', [WalletController::class, 'getWallet']);
Route::get('/wallet/{walletUuid}/balance', [WalletController::class, 'getBalance']);
Route::post('/wallet/{walletUuid}/charge', [WalletController::class, 'chargeWallet']);

Route::post('/transaction/{userId}', [TransactionController::class, 'createTransaction']);
Route::get('/transaction/{userId}', [TransactionController::class, 'getTransactions']);
Route::post('/transaction/{transactionUuid}/payment', [TransactionController::class, 'chargePayment']);

