<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_user_can_login_successfully()
    {
        User::factory()->create();
        $this->postJson('/api/login', [
            'email' => 'mehdi@aktaa-dental.com',
            'password' => '123456'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => ['access_token', 'user']
            ])
            ->assertSee('mehdi@aktaa-dental.com');
    }

    /**
     * @test
     */
    public function test_user_send_wrong_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'fake@aktaa-dental.com',
            'password' => '123456'
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                "errors" => ["email"]
            ]);
//        $this->expectException(ValidationException::class);

    }

    /**
     * @test
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*'], 'api');

        $this->postJson('/api/logout')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertCount(0, $user->tokens);
//        $this->assertGuest('api');
    }
}
