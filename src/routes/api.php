<?php

use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TradeItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::post('/explorer', [ExplorerController::class, 'store']); // Obrigatório
Route::post('/explorers/trade', [ItemController::class, 'trade']); // Obrigatório

// Login
Route::post('/login', [ExplorerController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Explorer
    Route::put('/explorer/{id}', [ExplorerController::class, 'update']); // Obrigatório
    Route::get('/explorer/{id}', [ExplorerController::class, 'show']); // Obrigatório

    // Items
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/inventory', [ItemController::class, 'store']); // Obrigatório

    // History
    Route::get('/explorer/{id}/history', [HistoryController::class, 'index']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
