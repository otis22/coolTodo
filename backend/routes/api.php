<?php

declare(strict_types=1);

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

use App\Infrastructure\Http\Controllers\TodoController;

Route::get('/todos', [TodoController::class, 'index']);
Route::post('/todos', [TodoController::class, 'store']);
Route::put('/todos/{id}', [TodoController::class, 'update'])->where('id', '[0-9]+');
Route::patch('/todos/{id}/status', [TodoController::class, 'updateStatus'])->where('id', '[0-9]+');
Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->where('id', '[0-9]+');
Route::delete('/todos/completed', [TodoController::class, 'destroyCompleted']);







