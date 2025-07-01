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

test('非ログイン時、Quoot削除しようとするとログイン画面にリダイレクト', function(){
    $response = $this->delete('/api/quoot/delete/1');
    $response->assertStatus(401);
});

test('別ユーザでは、Quoot削除できない', function(){
    $token = $this->user2->createToken('AccessToken')->plainTextToken;
    $response = $this->delete('/api/quoot/delete/1',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(403);
});

test('ログイン後、Quoot削除しレスポンスが返る', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $response = $this->delete('/api/quoot/delete/1',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(204);
});

test('ログイン後、Quoot削除しDBが更新される', function(){
    $token = $this->user->createToken('AccessToken')->plainTextToken;
    $this->delete('/api/quoot/delete/1',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseMissing('quoots',['content'=>'Test Quoot']);
});
