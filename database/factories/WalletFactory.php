<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->lastName(),
            'address' => $this->faker->unique()->bothify('?###??##??####???###?'),
            'balance' => $this->faker->randomNumber(2),
            'user_id' => User::inRandomOrder()->first()->id,
            'wallet_type_id' => WalletType::inRandomOrder()->first()->id,
        ];
    }
}
