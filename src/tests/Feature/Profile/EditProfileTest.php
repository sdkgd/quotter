<?php

use App\Models\Quser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

$user;
beforeEach(function(){
    $this->user=Quser::factory()->create([
        'user_name'=>'Arnold',
        'display_name'=>'Arnoldy',
        'profile'=>'Test User',
    ]); 
});

test('非ログイン時、プロフィール編集ページに移動しようとするとログイン画面にリダイレクト', function(){
    $response = $this->get('/api/user/'.$this->user->user_name.'/edit');
    $response->assertStatus(401);
});

test('非ログイン時、プロフィール編集アクションを実行しようとするとログイン画面にリダイレクト', function(){
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit');
    $response->assertStatus(401);
});

test('ログイン時、「プロフィールを編集」ボタン押下で編集ページに移動', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->get('/api/user/'.$this->user->user_name.'/edit',[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(200);
});

test('プロフィール編集を実行しリダイレクトされる', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Edited Test User',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(204);
});

test('プロフィール編集 バリデーションが正しく機能する', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'表示名 は必須入力です',]);

    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'表示名 は 255 文字以下で入力してください',]);

    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'自己紹介 は 255 文字以下で入力してください',]);

    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(204);
    
});

test('プロフィール編集を実行しDBが更新される', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $this->assertDatabaseHas('qusers',[
        'display_name'=>'Arnoldy',
        'profile'=>'Test User',
    ]);
    $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Edited Test User',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseMissing('qusers',[
        'display_name'=>'Arnoldy',
        'profile'=>'Test User',
    ]);
    $this->assertDatabaseHas('qusers',[
        'display_name'=>'Edited Arnoldy',
        'profile'=>'Edited Test User',
    ]);
});

test('プロフィール編集 画像アップロード', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;

    $uploadFile=new UploadedFile(
        './tests/img/profile_icon.png',
        'profile_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(204);

    $quser=Quser::where(['user_name'=>'Arnold',])->first();
    if($this->app->environment(['production','ci'])){
        $path=$quser->getImagePath();
        $div=preg_split("/\//",$path);
        Storage::disk('s3')->delete(end($div));
    }else{
        Storage::disk('public')->delete($quser->getImagePath());
    }

});

test('プロフィール編集 画像アップロード DB,Storage確認', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;

    $uploadFile=new UploadedFile(
        './tests/img/profile_icon.png',
        'profile_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(204);

    $quser=Quser::where(['user_name'=>'Arnold',])->first();
    if($this->app->environment(['production','ci'])){
        $path=$quser->getImagePath();
        $div=preg_split("/\//",$path);
        Storage::disk('s3')->assertExists(end($div));
        Storage::disk('s3')->delete(end($div));
    }else{
        Storage::disk('public')->assertExists($quser->getImagePath());
        Storage::disk('public')->delete($quser->getImagePath());
    }
    
});

test('プロフィール編集 画像アップロード バリデーション', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;

    $uploadFile=new UploadedFile(
        './tests/img/large_icon.png',
        'large_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'プロフィール画像 には 1024 キロバイト以下の画像を指定してください',]);

    $uploadFile=new UploadedFile(
        './tests/img/sample.txt',
        'sample.txt',
        'text/plain',
        null,
        true
    );
    
    $response = $this->put('/api/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'プロフィール画像 には画像を指定してください (and 1 more error)',]);
    
});