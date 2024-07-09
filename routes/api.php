<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('posts', PostController::class);
    Route::post('comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, '']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});

Route::get('/blah', function(){
    return response()->json([
        'aa' => 'aa 11',
        'bb' => 'aa 22 22',
        'cc' => 'aa 33 33 33',
    ], 202);
});

