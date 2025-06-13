<?php

use App\Models\Quser;
use App\Models\Quoot;

$user;
$user2;
$quoot;

//事前にユーザを2名作成し、1人目でQuootを1つ作っておく
beforeEach(function(){
    $this->user=Quser::factory()->create();
    $this->user2=Quser::factory()->create();
    $this->quoot=Quoot::factory()->create([
        'id'=>1,
        'user_id'=>$this->user->id,
        'content'=>'Test Quoot',
    ]);
});

test('非ログイン時、Quoot更新画面に移動するとログイン画面にリダイレクト', function(){
    $response = $this->get('/quoot/update/1');
    $response->assertRedirect('/login');
});

test('非ログイン時、Quoot更新しようとするとログイン画面にリダイレクト', function(){
    $response = $this->put('/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ]);
    
    $response->assertRedirect('/login');
});

test('別ユーザでは、Quoot更新画面に移動できない', function(){
    $this->post('/login', [
        'email' => $this->user2->email,
        'password' => 'password',
    ]);
    $response = $this->get('/quoot/update/1');
    $response->assertStatus(403);
});

test('別ユーザでは、Quoot更新できない', function(){
    $this->post('/login', [
        'email' => $this->user2->email,
        'password' => 'password',
    ]);
    $response = $this->put('/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ]);
    $response->assertStatus(403);
});

test('ログイン後、Quoot更新画面に移動できる', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->get('/quoot/update/1');
    $response->assertStatus(200);
});

test('ログイン後、Quoot更新しレスポンスが返る', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->put('/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ]);
    
    $response->assertRedirect('/quoot');
    $response = $this->get('/quoot');
    $response->assertSee('Edited Test Quoot');
});

test('ログイン後、Quoot更新しDBが更新される', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $this->put('/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ]);

    $this->assertDatabaseHas('quoots',['content'=>'Edited Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容では更新できない', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->put('/quoot/update/1',[
        'quoot'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
    ])->assertInvalid(['quoot'=>'つぶやき は 140 文字以下で入力してください',]);
});
