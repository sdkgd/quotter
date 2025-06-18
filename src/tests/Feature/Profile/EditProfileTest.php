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

test('非ログイン時、表示名と自己紹介が表示され、「プロフィールを編集」ボタンが表示されない', function(){
    $response = $this->get('/user/'.$this->user->user_name);
    $response->assertSee($this->user->display_name);
    $response->assertSee($this->user->profile);
    $response->assertDontSee('プロフィールを編集');
});

test('非ログイン時、プロフィール編集ページに移動しようとするとログイン画面にリダイレクト', function(){
    $response = $this->get('/user/'.$this->user->user_name.'/edit');
    $response->assertRedirect('/login');
});

test('非ログイン時、プロフィール編集アクションを実行しようとするとログイン画面にリダイレクト', function(){
    $response = $this->put('/user/'.$this->user->user_name.'/edit');
    $response->assertRedirect('/login');
});

test('ログイン時、「プロフィールを編集」ボタン押下で編集ページに移動できる', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->user->user_name);
    $response->assertSee($this->user->display_name);
    $response->assertSee($this->user->profile);
    $response->assertSee('プロフィールを編集');
    $response = $this->get('/user/'.$this->user->user_name.'/edit');
    $response->assertStatus(200);
});

test('プロフィール編集ページに表示名と自己紹介が表示される', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->user->user_name.'/edit');
    $response->assertSee($this->user->display_name);
    $response->assertSee($this->user->profile);
});

test('プロフィール編集を実行しリダイレクトされる', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Edited Test User',
    ]);
    $response->assertRedirect('/user/'.$this->user->user_name);
    $response = $this->get('/user/'.$this->user->user_name);
    $response->assertSee('Edited Arnoldy');
    $response->assertSee('Edited Test User');
});

test('プロフィール編集 バリデーションが正しく機能する', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'',
    ])->assertInvalid(['input1'=>'表示名 は必須入力です',]);

    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ])->assertInvalid(['input1'=>'表示名 は 255 文字以下で入力してください',]);

    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ])->assertInvalid(['input2'=>'自己紹介 は 255 文字以下で入力してください',]);

    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'',
    ])->assertValid();
});

test('プロフィール編集を実行しDBが更新される', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $this->assertDatabaseHas('qusers',[
        'display_name'=>'Arnoldy',
        'profile'=>'Test User',
    ]);
    $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Edited Arnoldy',
        'input2'=>'Edited Test User',
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
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $uploadFile=new UploadedFile(
        './tests/img/profile_icon.png',
        'profile_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ])->assertValid();
    $response->assertRedirect('/user/'.$this->user->user_name);

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
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $uploadFile=new UploadedFile(
        './tests/img/profile_icon.png',
        'profile_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ])->assertValid();

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
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $uploadFile=new UploadedFile(
        './tests/img/large_icon.png',
        'large_icon.png',
        'image/png',
        null,
        true
    );
    
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ])->assertInvalid(['input3'=>'プロフィール画像 には 1024 キロバイト以下の画像を指定してください',]);

    $uploadFile=new UploadedFile(
        './tests/img/sample.txt',
        'sample.txt',
        'text/plain',
        null,
        true
    );
    
    $response = $this->put('/user/'.$this->user->user_name.'/edit',[
        'input1'=>'Arnoldy',
        'input2'=>'Test User',
        'input3'=>$uploadFile,
    ])->assertInvalid(['input3'=>'プロフィール画像 には画像を指定してください',]);
    
});
