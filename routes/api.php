<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/create', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'store']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/lesson',[LessonController::class,'index']);
    Route::post('/lesson/subscribe', [LessonController::class, 'subscribeLesson']);
    Route::put('/lesson/{id}/unsubscribe',[LessonController::class,'unsubscribeLesson']);
    Route::get('/lesson/subscribed',[LessonController::class,'listLesson']);
});