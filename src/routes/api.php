<?php

use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TradeItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Explorer
Route::get('/explorers', [ExplorerController::class, 'index']);
Route::post('/explorer', [ExplorerController::class, 'store']);
Route::put('/explorer/{id}', [ExplorerController::class, 'update']);
Route::get('/explorer/{id}', [ExplorerController::class, 'show']);


// Items
Route::get('/items', [ItemController::class, 'index']);
Route::post('/explorers/{id}/inventory', [ItemController::class, 'store']);

// Trades
Route::get('/trades', [TradeController::class, 'index']);
Route::post('/trade', [TradeController::class, 'store']);
Route::delete('/trade/{id}', [TradeController::class, 'destroy']);
Route::post('/trades/{id}/completed', [TradeController::class, 'completeExchange']);

// Trade Item
Route::get('/tradeItems', [TradeItemsController::class, 'index']);
Route::post('/tradeItem', [TradeItemsController::class, 'store']);

// History
Route::get('/explorer/{id}/history', [HistoryController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
