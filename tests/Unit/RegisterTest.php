<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegisterTest extends TestCase {

    public function testRequiredFieldsForRegister() {
        $this->postJson(route('auth.register'))
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => ['The first name field is required.'],
                    'last_name' => ['The last name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function testPasswordDoesNotMatch() {

        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testlogin@app.com',
            'password' => 'password',
            'password_confirmation' => 'passwords',
        ];

        $this->postJson(route('auth.register'), $payload)
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.'],
                ]
            ]);
    }

    public function testPasswordLength() {

        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testlogin@ewallets.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];

        $this->postJson(route('auth.register'), $payload)
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password must be at least 6 characters.'],
                ]
            ]);
    }

    public function testInvalidEmailAddress() {

        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testlogin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson(route('auth.register'), $payload)
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ]
            ]);
    }

    public function testEmailAddressAlreadyExists() {

        $user = User::factory()->create();

        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson(route('auth.register'), $payload)
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ]
            ]);
    }

    public function testUserCanRegisterSuccessfully() {

        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testlogin@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson(route('auth.register'), $payload)
            ->assertStatus(201)
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

}
