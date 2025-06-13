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
    $response = $this->delete('/quoot/delete/1');
    $response->assertRedirect('/login');
});

test('別ユーザでは、Quoot削除できない', function(){
    $this->post('/login', [
        'email' => $this->user2->email,
        'password' => 'password',
    ]);
    $response = $this->delete('/quoot/delete/1');
    $response->assertStatus(403);
});

test('ログイン後、Quoot削除しレスポンスが返る', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $response = $this->delete('/quoot/delete/1');
    $response->assertRedirect('/quoot');
    $response = $this->get('/quoot');
    $response->assertDontSee('Test Quoot');
});

test('ログイン後、Quoot削除しDBが更新される', function(){
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);
    $this->delete('/quoot/delete/1');
    $this->assertDatabaseMissing('quoots',['content'=>'Test Quoot']);
});
