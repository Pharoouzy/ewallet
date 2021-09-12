<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase {

    public function testRequiredFieldsForLogin() {

        $this->postJson(route('auth.login'))
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }


    public function testUserCanLoginSuccessfully() {

        User::factory()->create([
            'email' => 'testlogin@ewallets.com',
            'password' => Hash::make('password'),
        ]);

        $payload = ['email' => 'testlogin@ewallets.com', 'password' => 'password'];

        $this->postJson(route('auth.login'), $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'user' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

    }

    public function testInvalidLoginCredentials() {
        User::factory()->create([
            'email' => 'testlogin@ewallets.com',
            'password' => Hash::make('password'),
        ]);

        $payload = ['email' => 'testuser@ewallets.com', 'password' => 'password'];

        $this->postJson(route('auth.login'), $payload)
            ->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized credentials.',
            ]);

    }

    public function testUserCanLogoutSuccessfully() {

        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->postJson(route('auth.logout'))
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User logged out from API successfully.',
            ]);

    }
}
