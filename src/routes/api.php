<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Quser;
use App\Models\Quoot;
use App\Models\Follows;
use Illuminate\Support\Facades\Storage;
use App\Models\Chat;

Route::get('/test', function (Request $request) {
   return response()->json([
       'message' => "This is a test message from API.",
    ],200);
});

Route::post('/test/reset-db', function () {
    if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
    }
    Artisan::call('migrate:fresh --seed');
    return response()->json(['message' => 'Database reset'], 200);
});

Route::post('/test/create-testuser', function () {
   if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
   }
   $user = Quser::factory()->create([
      'profile'=>'Test User',
   ]);
   return response()->json([
      'id' => $user->id,
      'user_name' => $user->user_name,
      'display_name' => $user->display_name,
      'email' => $user->email,
      'profile' => $user->profile,
   ],201);
});

Route::post('/test/create-testquoot', function (Request $request) {
   if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
   }
   $quoot = Quoot::factory()->create([
      'user_id'=>$request->user_id,
      'content'=>$request->quoot,
   ]);
   return response()->json([
      'id' => $quoot->id,
   ],201);
});

Route::post('/test/create-testfollow', function (Request $request) {
   if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
   }
   $follow = Follows::factory()->create([
      'following_user_id'=>$request->following_id,
      'followed_user_id'=>$request->followed_id,
   ]);
   return response()->json([
      'id' => $follow->id,
   ],201);
});

Route::post('/test/clear-image-disk', function (Request $request) {
   if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
   }
   $quser=Quser::where(['user_name'=>$request->user_name,])->first();
   if(env('APP_ENV') === 'ci'){
      $path=$quser->getImagePath();
      $div=preg_split("/\//",$path);
      Storage::disk('s3')->delete(end($div));
   }else{
      Storage::disk('public')->delete($quser->getImagePath());
   }
});

Route::post('/test/create-testchat', function (Request $request) {
   if (env('APP_ENV') === 'production') {
      return response()->json(['error' => 'Forbidden'], 403);
   }
   $chat=Chat::factory()->create([
      'user1_id'=>$request->user1_id,
      'user2_id'=>$request->user2_id,
   ]);
   return response()->json([
      'id' => $chat->id,
   ],201);
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
