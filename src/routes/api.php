<?php

use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\ItemController;
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

// Items
Route::get('/items', [ItemController::class, 'index']);
Route::post('/item', [ItemController::class, 'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
