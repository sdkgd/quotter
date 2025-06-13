<?php

use App\Models\Quser;
use App\Models\Quoot;
use App\Models\Follows;

$users=array(5);
$quoots=array(5);
$names=array(5);
$follows=array(4);

beforeEach(function(){
    $names=array("Arnold","Bobby","Carmelo","Davin","Eino");
    for($i=0;$i<5;$i++){
        $this->names[$i]=$names[$i];
    }
    for($i=0;$i<5;$i++){
        $this->users[$i]=Quser::factory()->create([
            'id'=>$i+1,
            'user_name'=>$this->names[$i],
            'display_name'=>$this->names[$i],
        ]);
    }
    for($i=0;$i<5;$i++){
        $this->quoots[$i]=Quoot::factory()->create([
            'id'=>$i+1,
            'user_id'=>$this->users[$i]->id,
            'content'=>'I am '.$this->users[$i]->user_name,
        ]);
    }
    
    $this->follows[0]=Follows::factory()->create([
        'id'=>1,
        'following_user_id'=>1,
        'followed_user_id'=>3,
    ]);
    $this->follows[1]=Follows::factory()->create([
        'id'=>2,
        'following_user_id'=>2,
        'followed_user_id'=>3,
    ]);
    $this->follows[2]=Follows::factory()->create([
        'id'=>3,
        'following_user_id'=>3,
        'followed_user_id'=>4,
    ]);
    $this->follows[3]=Follows::factory()->create([
        'id'=>4,
        'following_user_id'=>3,
        'followed_user_id'=>5,
    ]);
});

test('非ログイン時、全ユーザのQuootが表示される', function(){
    $response = $this->get('/quoot');
    for($i=0;$i<5;$i++){
        $response->assertSee('I am '.$this->users[$i]->user_name);
    }
});

test('非ログイン時、フォローリストを表示しようとするとログイン画面にリダイレクト', function(){
    $response = $this->get('/user/'.$this->users[2]->user_name.'/follows');
    $response->assertRedirect('/login');
});

test('非ログイン時、フォロワーリストを表示しようとするとログイン画面にリダイレクト', function(){
    $response = $this->get('/user/'.$this->users[2]->user_name.'/followers');
    $response->assertRedirect('/login');
});

test('ログイン時、自分及びフォロー中のユーザのQuootのみが表示される', function(){
    $response=$this->post('/login', [
        'email' => $this->users[2]->email,
        'password' => 'password',
    ]);
    
    $response = $this->get('/quoot');
    for($i=0;$i<2;$i++){
        $response->assertDontSee('I am '.$this->users[$i]->user_name);
    }
    for($i=2;$i<5;$i++){
        $response->assertSee('I am '.$this->users[$i]->user_name);
    }
});

test('ログイン時、フォローリストにフォロー中のユーザが表示される', function(){
    $this->post('/login', [
        'email' => $this->users[2]->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->users[2]->user_name.'/follows');
    for($i=0;$i<2;$i++){
        $response->assertDontSee($this->users[$i]->user_name);
    }
    for($i=3;$i<5;$i++){
        $response->assertSee($this->users[$i]->user_name);
    }
});

test('ログイン時、フォロワーリストに自分をフォローしているユーザが表示される', function(){
    $this->post('/login', [
        'email' => $this->users[2]->email,
        'password' => 'password',
    ]);
    $response = $this->get('/user/'.$this->users[2]->user_name.'/followers');
    for($i=3;$i<5;$i++){
        $response->assertDontSee($this->users[$i]->user_name);
    }
    for($i=0;$i<2;$i++){
        $response->assertSee($this->users[$i]->user_name);
    }
});
