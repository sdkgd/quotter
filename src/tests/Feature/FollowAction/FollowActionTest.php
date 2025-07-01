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

test('非ログイン時、フォローアクションしようとするとログイン画面にリダイレクト', function(){
    $response = $this->post('/api/user/'.$this->users[1]->user_name.'/follow');
    $response->assertStatus(401);
});

test('非ログイン時、フォロー解除アクションしようとするとログイン画面にリダイレクト', function(){
    $response = $this->delete('/api/user/'.$this->users[1]->user_name.'/unfollow');
    $response->assertStatus(401);
});

test('ログイン時、「フォローする」ボタン押下でレスポンスが返る', function(){
    $token = $this->users[0]->createToken('AccessToken')->plainTextToken;
    $response = $this->post('/api/user/'.$this->users[1]->user_name.'/follow',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(201);
});

test('ログイン時、「フォローする」ボタン押下でDBにフォロー関係が構築される', function(){
    $token = $this->users[0]->createToken('AccessToken')->plainTextToken;
    $this->post('/api/user/'.$this->users[1]->user_name.'/follow',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseHas('follows',[
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
});

test('ログイン時、「フォロー解除」ボタン押下でレスポンスが返る', function(){
    $follow=Follows::factory()->create([
        'id'=>1,
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
    $token = $this->users[0]->createToken('AccessToken')->plainTextToken;
    $response = $this->delete('/api/user/'.$this->users[1]->user_name.'/unfollow',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response->assertStatus(204);
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
    $token = $this->users[0]->createToken('AccessToken')->plainTextToken;
    $this->delete('/api/user/'.$this->users[1]->user_name.'/unfollow',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $this->assertDatabaseMissing('follows',[
        'following_user_id'=>1,
        'followed_user_id'=>2,
    ]);
});