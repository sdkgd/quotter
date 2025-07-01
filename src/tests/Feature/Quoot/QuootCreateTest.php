<?php

use App\Models\Quser;

$user;
beforeEach(function(){
    $this->user=Quser::factory()->create();
});

test('非ログイン時、Quoot投稿しようとするとログイン画面にリダイレクト', function(){
    $response = $this->post('/api/quoot/create',[
        'quoot'=>'Test Quoot',
    ]);
    $response->assertStatus(401);
});

test('ログイン後、Quoot投稿しレスポンスが返る', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->post('/api/quoot/create',[
        'quoot'=>'Test Quoot',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(201);
});

test('ログイン後、Quoot投稿しDBが更新される', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $this->post('/api/quoot/create',[
        'quoot'=>'Test Quoot',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseHas('quoots',['content'=>'Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容は投稿できない', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->post('/api/quoot/create',[
        'quoot'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'つぶやき は 140 文字以下で入力してください',]);
});
