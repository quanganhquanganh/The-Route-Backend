<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoadmapController;
use App\Http\Controllers\Api\MilestoneController;
use App\Http\Controllers\Api\TaskController;

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

// Route::get('/show-roadmap/{id}', [RoadmapController::class, 'show']);
// Route::get('/show-milestone/{id}', [MilestoneController::class, 'show']);
// Route::get('/show-task/{id}', [TaskController::class, 'show']);
Route::resource('/roadmaps', RoadmapController::class);
Route::resource('/milestones', MilestoneController::class);
Route::resource('/tasks', TaskController::class);
