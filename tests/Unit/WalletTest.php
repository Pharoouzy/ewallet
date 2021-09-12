<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WalletTest extends TestCase {

    public function testRequiredFields() {

        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->postJson(route('wallets.create'))
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'type' => ['The type field is required.'],
                    'type.name' => ['The type.name field is required.'],
                    'type.min_balance' => ['The type.min balance field is required.'],
                    'type.monthly_interest_rate' => ['The type.monthly interest rate field is required.'],
                ]
            ]);
    }

    public function testTypeFieldMustBeArray() {

        Sanctum::actingAs(User::factory()->create(), ['*']);

        $payload = [
            'name' => 'Flex',
            'type' => '',
        ];

        $this->postJson(route('wallets.create', $payload))
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'type' => ['The type must be an array.', 'The type field is required.'],
                    'type.name' => ['The type.name field is required.'],
                    'type.min_balance' => ['The type.min balance field is required.'],
                    'type.monthly_interest_rate' => ['The type.monthly interest rate field is required.'],
                ]
            ]);
    }

    public function testUserCanCreateWalletSuccessfully() {

        Sanctum::actingAs(User::factory()->create(), ['*']);

        $payload = [
            'name' => 'Flex',
            'type' => [
                'name' => 'Savings',
                'min_balance' => 10,
                'monthly_interest_rate' => 2,
            ],
        ];

        $this->postJson(route('wallets.create'), $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'user_id',
                    'address',
                    'balance',
                    'wallet_type_id',
                    'created_at',
                    'updated_at',
                    'type' => [
                        'name',
                        'min_balance',
                        'monthly_interest_rate'
                    ],
                ],
            ]);

    }

}
