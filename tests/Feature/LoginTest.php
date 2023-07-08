<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_user_login_with_correct_credentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    public function test_user_login_with_invalid_credentials()
    {
        $response = $this->postJson('api/v1/login', [
            'email' => 'hello@hello.com',
            'password' => 'qw85teebd'
        ]);
        $response->assertStatus(422);
    }
}
