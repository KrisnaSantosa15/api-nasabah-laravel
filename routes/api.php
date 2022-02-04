<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers/add', [CustomerController::class, 'store']);

Route::get('transactions', [TransactionController::class, 'index']);
Route::post('transactions/add', [TransactionController::class, 'store']);

Route::get('points', [TransactionController::class, 'getAllPoints']);
Route::get('points/{customer_id}', [TransactionController::class, 'getPointsById']);

Route::post('reports', [ReportController::class, 'getReports']);
