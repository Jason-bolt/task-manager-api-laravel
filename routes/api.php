<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::controller(UserController::class)->prefix('auth')->group(function () {
        Route::get('/', 'index');
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });

    Route::controller(TaskController::class)->prefix('tasks')->middleware(['auth:sanctum', 'log.activity'])->group(function () {
        Route::get('/', 'index');
        Route::post('/create', 'store');
        Route::put('/update/{taskId}', 'update');
        Route::delete('/delete/{taskId}', 'destroy');
    });
});
