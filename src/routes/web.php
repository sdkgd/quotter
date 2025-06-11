<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('/quoot')->group(function(){
   Route::get('', \App\Http\Controllers\Quoot\IndexController::class)->name('quoot.index');
});

Route::prefix('/quoot')->middleware('auth')->group(function(){
   Route::get('/create', \App\Http\Controllers\Quoot\Create\CreateController::class)->name('quoot.create');
   Route::post('/create', \App\Http\Controllers\Quoot\Create\PostController::class);
   Route::get('/update/{quootId}', \App\Http\Controllers\Quoot\Update\UpdateController::class)->name('quoot.update');
   Route::put('/update/{quootId}', \App\Http\Controllers\Quoot\Update\PutController::class)->name('quoot.update.put');
   Route::delete('/delete/{quootId}', \App\Http\Controllers\Quoot\Delete\DeleteController::class)->name('quoot.delete');
});

Route::prefix('/chat')->middleware('auth')->group(function(){
   Route::get('/{chatId}', \App\Http\Controllers\Chat\ChatController::class)->name('chat.index');
   Route::post('/{chatId}', \App\Http\Controllers\Chat\PostController::class);
});

Route::prefix('/user/{userName}')->group(function(){
   Route::get('', \App\Http\Controllers\User\UserController::class)->name('user.index');
});

Route::prefix('/user/{userName}')->middleware('auth')->group(function(){
   Route::post('/chat', \App\Http\Controllers\Chat\MakeChatRoomController::class);
   Route::post('/follow', \App\Http\Controllers\User\FollowAction\FollowUserController::class);
   Route::delete('/unfollow', \App\Http\Controllers\User\FollowAction\UnFollowUserController::class);
   Route::get('/follows', \App\Http\Controllers\User\FollowsController::class)->name('user.follows');
   Route::get('/followers', \App\Http\Controllers\User\FollowersController::class)->name('user.followers');
   Route::get('/edit', \App\Http\Controllers\User\Edit\EditController::class)->name('user.edit');
   Route::put('/edit', \App\Http\Controllers\User\Edit\EditPutController::class)->name('user.edit.put');
});