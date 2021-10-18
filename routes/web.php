<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [TaskController::class, 'index']);
Route::get('/task/info/{id}', [TaskController::class, 'info']);
Route::post('/task/update', [TaskController::class, 'update']);
Route::delete('/task/destroy/{id}', [TaskController::class, 'destroy']);