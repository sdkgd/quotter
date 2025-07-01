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

test('非ログイン時、Quoot更新しようとするとログイン画面にリダイレクト', function(){
    $response = $this->put('/api/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ]);
    $response->assertStatus(401);
});

test('別ユーザでは、Quoot更新できない', function(){
    $token = $this->user2->createToken('AccessToken')->plainTextToken;
    $response = $this->put('/api/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(403);
});

test('ログイン後、Quoot更新しレスポンスが返る', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->put('/api/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(204);
});

test('ログイン後、Quoot更新しDBが更新される', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $this->put('/api/quoot/update/1',[
        'quoot'=>'Edited Test Quoot',
    ],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseHas('quoots',['content'=>'Edited Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容では更新できない', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->put('/api/quoot/update/1',[
        'quoot'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
    ],[
        'Authorization' => 'Bearer '.$token,
    ])->assertStatus(422)->assertJson(['message'=>'つぶやき は 140 文字以下で入力してください',]);
});
