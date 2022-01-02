<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoadmapController;
use App\Http\Controllers\Api\MilestoneController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TestController;

use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::resource('/roadmaps', RoadmapController::class);
// Route::resource('/milestones', MilestoneController::class);
// Route::resource('/tasks', TaskController::class);
// Route::get('/tests/{test:slug}', [TestController::class, 'show']);
// Route::get('/tests', [TestController::class, 'index']);
// Route::post('/tests', [TestController::class, 'store']);
// Route::put('/tests/{test:slug}', [TestController::class, 'update']);

// Route::get('/roadmaps/{id}/full', [RoadmapController::class, 'full']);

// Route::get('roadmaps/{roadmap}/milestones', 'MilestoneController@index');

// Route::get('/tasks/{id}/complete', 'TaskController@complete');
// Route::get('/tasks/{id}/incomplete', 'TaskController@incomplete');

Route::group([
    'middleware' => 'api',
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    //Roadmap Routes
    Route::get('/users/{user:username}/roadmaps', [RoadmapController::class, 'index']);
    Route::get('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'show']);
    Route::get('/roadmaps/{roadmap:slug}/full', [RoadmapController::class, 'full']);
    Route::post('/roadmaps', [RoadmapController::class, 'store']);
    Route::put('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'update']);
    Route::delete('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'destroy']);

    //Milestone Routes
    Route::get('/users/{user:username}/milestones', [MilestoneController::class, 'index']);
    Route::post('/milestones', [MilestoneController::class, 'store']);
    Route::put('/milestones/{id}', [MilestoneController::class, 'update']);
    Route::delete('/milestones/{id}', [MilestoneController::class, 'destroy']);

    //Task Routes
    Route::get('/users/{user:username}/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});
