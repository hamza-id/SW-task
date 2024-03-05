<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\User;
use Faker\Factory as Faker;

class AuthControllerTest extends TestCase
{
    public function test_user_can_register_successfully()
    {
        $faker = Faker::create();

        $userData = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->safeEmail,
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(200)
            ->assertJson([
                'code'    => 200,
                'message' => 'User Registration Successfully!',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'hamza@example.com']);
    }

    public function test_user_registration_fails_missing_data()
    {
        $userData = [
            'name' => 'Hamza',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(422);
    }

    public function test_user_registration_fails_wrong_data()
    {
        $userData = [
            'name'  => 'Hamza',
            'email' => 'Hamzagmail.com',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(422);
    }

    public function test_user_can_login_successfully()
    {
        $credentials = [
            'email'    => 'hamza@example.com',
            'password' => 'admin123',
        ];

        $response = $this->postJson('/api/auth/login', $credentials);
        $response->assertStatus(200)->assertJson([
            'code'    => 200,
            'message' => 'Login Successfully!',
        ]);
    }

    public function test_user_login_fails_with_incorrect_credentials()
    {
        $credentials = [
            'email'    => 'hamza@example.com',
            'password' => 'admin1234',
        ];

        $response = $this->postJson('/api/auth/login', $credentials);
        $response->assertStatus(200)->assertJson([
            'code'    => 200,
            'message' => 'Email or password does not match!',
        ]);
    }

    public function test_user_can_logout_successfully()
    {
        $user  = User::factory()->create();
        $token = $user->createToken('sw-task')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');
        $response->assertStatus(200)->assertJson([
            'code'    => 200,
            'message' => 'Successfully logged out!',
        ]);
    }

    public function test_user_can_fetch_details()
    {
        $user  = User::factory()->create();
        $token = $user->createToken('sw-task')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
