<?php

use App\Models\Quser;

test('users can authenticate using the login screen', function () {
    $user = Quser::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(201);
});

test('users can not authenticate with invalid password', function () {
    $user = Quser::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});

test('users can logout', function () {
    $user = Quser::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $content = $response->content();
    $content_array = json_decode($content,true);
    $token = $content_array['token'];

    $response2 = $this->post('/api/logout',[],[
        'Authorization' => 'Bearer '.$token,
    ]);
    $response2->assertStatus(201);
});
