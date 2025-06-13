<?php

use App\Models\Quser;
use App\Models\Chat;

$users=array(3);
$names=array(3);
$chat;

beforeEach(function(){
    $names=array("Arnold","Bobby","Carmelo");
    for($i=0;$i<3;$i++){
        $this->names[$i]=$names[$i];
    }
    for($i=0;$i<3;$i++){
        $this->users[$i]=Quser::factory()->create([
            'id'=>$i+1,
            'user_name'=>$this->names[$i],
            'display_name'=>$this->names[$i],
        ]);
    }
});

test('チャットルームが存在しない場合、「チャットを開始」ボタン押下で新たに作成される', function(){
    $response=$this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $this->assertDatabaseMissing('chats',[
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('チャットを開始');
    $response=$this->post('/user/'.$this->users[1]->user_name.'/chat',[
        'id'=>1,
    ]);
    $response->assertRedirect('/chat/1');
    $this->assertDatabaseHas('chats',[
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
});

test('チャットルームが存在する場合、「チャットを開始」ボタン押下で該当のチャットルームに移動する', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $this->assertDatabaseHas('chats',[
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $response=$this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('チャットを開始');
    $response=$this->post('/user/'.$this->users[1]->user_name.'/chat',[
        'id'=>1,
    ]);
    $response->assertRedirect('/chat/1');
    $this->assertDatabaseCount('chats',1);
});

test('非ログイン時、チャットルームを閲覧しようとするとログイン画面にリダイレクト', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->get('/chat/1');
    $response->assertRedirect('/login');
});

test('非ログイン時、メッセージを投稿しようとするとログイン画面にリダイレクト', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->post('/chat/1', [
        'message' => 'Hello!',
    ]);
    $response->assertRedirect('/login');
});

test('ルームメンバー以外はチャットルームを閲覧、投稿できない', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->post('/login', [
        'email' => $this->users[2]->email,
        'password' => 'password',
    ]);
    $response=$this->get('/chat/1');
    $response->assertStatus(403);
    $response=$this->post('/chat/1', [
        'message' => 'Hello!',
    ]);
    $response->assertStatus(403);
});

test('ルームメンバーはメッセージを投稿できる', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $response=$this->get('/chat/1');
    $response->assertStatus(200);
    $response=$this->post('/chat/1', [
        'message' => 'Hello!',
    ]);
    $response->assertRedirect('/chat/1');
    $response=$this->get('/chat/1');
    $response->assertSee('Hello!');
});

test('メッセージ投稿でDBが更新される', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $this->post('/chat/1', [
        'message' => 'Hello!',
    ]);
    $this->assertDatabaseHas('messages',[
        'mentioned_user_id'=>1,
        'content'=>'Hello!',
    ]);
});

test('バリデーションを満たさないメッセージは投稿できない', function(){
    $this->chat=Chat::factory()->create([
        'id'=>1,
        'user1_id'=>1,
        'user2_id'=>2,
    ]);
    $response=$this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $response=$this->post('/chat/1', [
        'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    ])->assertInvalid(['message'=>'メッセージ は 1000 文字以下で入力してください',]);
});