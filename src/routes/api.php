<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/test', function (Request $request) {
   return response()->json([
       'message' => "This is a test message from API.",
    ],200);
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::get('/user', [AuthController::class, 'user']);
   Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('/quoot')->group(function(){
    Route::get('', \App\Http\Controllers\Quoot\IndexController::class)->name('quoot.index');
});

Route::prefix('/quoot')->middleware('auth:sanctum')->group(function(){
    Route::post('/create', \App\Http\Controllers\Quoot\Create\PostController::class);
    Route::get('/update/{quootId}', \App\Http\Controllers\Quoot\Update\UpdateController::class)->name('quoot.update');
    Route::put('/update/{quootId}', \App\Http\Controllers\Quoot\Update\PutController::class)->name('quoot.update.put');
    Route::delete('/delete/{quootId}', \App\Http\Controllers\Quoot\Delete\DeleteController::class)->name('quoot.delete');
 });
 
 Route::prefix('/chat')->middleware('auth:sanctum')->group(function(){
    Route::get('/{chatId}', \App\Http\Controllers\Chat\ChatController::class)->name('chat.index');
    Route::post('/{chatId}', \App\Http\Controllers\Chat\PostController::class);
 });
 
 Route::prefix('/user/{userName}')->group(function(){
    Route::get('', \App\Http\Controllers\User\UserController::class)->name('user.index');
 });
 
 Route::prefix('/user/{userName}')->middleware('auth:sanctum')->group(function(){
    Route::post('/chat', \App\Http\Controllers\Chat\MakeChatRoomController::class);
    Route::post('/follow', \App\Http\Controllers\User\FollowAction\FollowUserController::class);
    Route::delete('/unfollow', \App\Http\Controllers\User\FollowAction\UnFollowUserController::class);
    Route::get('/follows', \App\Http\Controllers\User\FollowsController::class)->name('user.follows');
    Route::get('/followers', \App\Http\Controllers\User\FollowersController::class)->name('user.followers');
    Route::get('/edit', \App\Http\Controllers\User\Edit\EditController::class)->name('user.edit');
    Route::put('/edit', \App\Http\Controllers\User\Edit\EditPutController::class)->name('user.edit.put');
 });
