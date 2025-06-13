<?php

use App\Models\Quser;
use App\Models\Follows;

$users=array(2);
$names=array(2);

beforeEach(function(){
    $names=array("Arnold","Bobby");
    for($i=0;$i<2;$i++){
        $this->names[$i]=$names[$i];
    }
    for($i=0;$i<2;$i++){
        $this->users[$i]=Quser::factory()->create([
            'id'=>$i+1,
            'user_name'=>$this->names[$i],
            'display_name'=>$this->names[$i],
        ]);
    }    
});

test('非ログイン時、ユーザページの「フォローする」「フォロー解除」ボタンが表示されない', function(){
    $response = $this->get('/user/'.$this->users[1]->user_name);
    $response->assertDontSee('フォローする');
    $response->assertDontSee('フォロー解除');
});

test('非ログイン時、フォローアクションしようとするとログイン画面にリダイレクト', function(){
    $response = $this->post('/user/'.$this->users[1]->user_name.'/follow');
    $response->assertRedirect('/login');
});

test('非ログイン時、フォロー解除アクションしようとするとログイン画面にリダイレクト', function(){
    $response = $this->delete('/user/'.$this->users[1]->user_name.'/unfollow');
    $response->assertRedirect('/login');
});

test('ログイン時、「フォローする」ボタン押下でリダイレクトされ、「フォロー解除」ボタンが表示される', function(){
    $this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('フォローする');
    $response = $this->post('/user/'.$this->users[1]->user_name.'/follow');
    $response->assertRedirect('/user/'.$this->users[1]->user_name);
    $response = $this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('フォロー解除');
});

test('ログイン時、「フォローする」ボタン押下でDBにフォロー関係が構築される', function(){
    $this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $this->post('/user/'.$this->users[1]->user_name.'/follow');
    $this->assertDatabaseHas('follows',[
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
});

test('ログイン時、「フォロー解除」ボタン押下でリダイレクトされ、「フォローする」ボタンが表示される', function(){
    $follow=Follows::factory()->create([
        'id'=>1,
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
    $this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('フォロー解除');
    $response = $this->delete('/user/'.$this->users[1]->user_name.'/unfollow');
    $response->assertRedirect('/user/'.$this->users[1]->user_name);
    $response = $this->get('/user/'.$this->users[1]->user_name);
    $response->assertSee('フォローする');
});

test('ログイン時、「フォロー解除」ボタン押下でDBのフォロー関係が削除される', function(){
    $follow=Follows::factory()->create([
        'id'=>1,
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
    $this->assertDatabaseHas('follows',[
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
    $this->post('/login', [
        'email' => $this->users[0]->email,
        'password' => 'password',
    ]);
    $this->delete('/user/'.$this->users[1]->user_name.'/unfollow');
    $this->assertDatabaseMissing('follows',[
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
});
