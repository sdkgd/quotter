<?php

use App\Models\Quser;

$user;
beforeEach(function(){
    $this->user=Quser::factory()->create();
});

test('非ログイン時、Quoot投稿画面に移動するとログイン画面にリダイレクト', function(){
    $response = $this->get('/quoot/create');
    $response->assertRedirect('/login');
});

test('非ログイン時、Quoot投稿しようとするとログイン画面にリダイレクト', function(){
    $response = $this->post('/quoot/create',[
        'quoot'=>'Test Quoot',
    ]);
    
    $response->assertRedirect('/login');
});

test('ログイン後、Quoot投稿画面に移動できる', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->get('/quoot/create');
    $response->assertStatus(200);
});

test('ログイン後、Quoot投稿しレスポンスが返る', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->post('/quoot/create',[
        'quoot'=>'Test Quoot',
    ]);
    
    $response->assertRedirect('/quoot');
    $response = $this->get('/quoot');
    $response->assertSee('Test Quoot');
});

test('ログイン後、Quoot投稿しDBが更新される', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $this->post('/quoot/create',[
        'quoot'=>'Test Quoot',
    ]);

    $this->assertDatabaseHas('quoots',['content'=>'Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容は投稿できない', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->post('/quoot/create',[
        'quoot'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
    ])->assertInvalid(['quoot'=>'つぶやき は 140 文字以下で入力してください',]);
});
