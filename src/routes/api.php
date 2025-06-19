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
