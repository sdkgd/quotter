<?php

test('new users can register', function () {
    $this->post('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response = $this->post('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
    $response->assertStatus(201);
});
