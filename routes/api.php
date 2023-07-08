<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TagController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\StatsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class , 'register']);
Route::post('/login', [UserController::class , 'login']);


Route::apiResource('tags' , TagController::class)->middleware('auth:sanctum');
Route::apiResource('posts' , PostController::class)->middleware('auth:sanctum');
Route::post('/restore/{id}' , [PostController::class , 'restore'])->middleware('auth:sanctum');
Route::get('/deletedPost' , [PostController::class , 'deletedPost'])->middleware('auth:sanctum');
Route::get('/stats' , [StatsController::class , 'stats'])->middleware('auth:sanctum');
