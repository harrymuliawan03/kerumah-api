<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess() {
        $this->post('/api/register', [
            'name' => 'Harry',
            'email' => 'harry100@gmail.com',
            'password' => 'testing',
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Harry',
                    'email' => 'harry100@gmail.com', 
                ]
                ]);
    }

    public function testRegisterFailed() {
        $this->post('/api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'], 
                    'password' => ['The password field is required.'], 
                ]
                ]);
    }

    public function testRegisterEmailExist() {
        $this->post('/api/register', [
            'name' => 'Harry',
            'email' => 'harry100@gmail.com',
            'password' => 'testing',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => ['email already registered.'],
                ]
                ]);
    }
}
