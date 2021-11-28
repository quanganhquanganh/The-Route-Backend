<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoadmapController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TodoController;

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

Route::get('/show-roadmap/{id}', [RoadmapController::class, 'show']);
Route::get('/get-tasks/{id}', [TaskController::class, 'index']);
Route::get('/get-todos/{id}', [TodoController::class, 'index']);