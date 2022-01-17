<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoadmapController;
use App\Http\Controllers\Api\MilestoneController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\HomePageController;

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

Route::group([
    'middleware' => 'api',
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    //HomePage routers
    Route::get('/highlight', [HomePageController::class, 'highlight']);
    Route::get('/menu', [HomePageController::class, 'menu']);
    Route::get('/myMenu', [HomePageController::class, 'myMenu']);

    //Roadmap Routes
    Route::get('/users/{user:username}/roadmaps', [RoadmapController::class, 'index']);
    Route::get('/users/{user:username}/liked-roadmaps', [RoadmapController::class, 'liked']);
    Route::get('/users/{user:username}/followed-roadmaps', [RoadmapController::class, 'followed']);
    Route::get('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'show']);
    Route::get('/roadmaps/{roadmap:slug}/full', [RoadmapController::class, 'full']);
    Route::post('/roadmaps', [RoadmapController::class, 'store']);
    Route::put('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'update']);
    Route::delete('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'destroy']);
    Route::post('/roadmaps/{roadmap:slug}', [RoadmapController::class, 'duplicate']);
    
    //Search roadmap
    Route::get('/roadmaps/search/{query}', [RoadmapController::class, 'search']);

    //Like and unlike
    Route::post('/roadmaps/{roadmap:slug}/like', [RoadmapController::class, 'like']);
    Route::delete('/roadmaps/{roadmap:slug}/unlike', [RoadmapController::class, 'unlike']);

    //Follow and unfollow
    Route::post('/roadmaps/{roadmap:slug}/follow', [RoadmapController::class, 'follow']);
    Route::delete('/roadmaps/{roadmap:slug}/unfollow', [RoadmapController::class, 'unfollow']);

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
