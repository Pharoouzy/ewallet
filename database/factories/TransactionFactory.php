<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reference' => $this->faker->bothify('?###??##'),
            'user_id' => User::inRandomOrder()->first()->id,
            'wallet_id' => Wallet::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomNumber(2),
            'new_balance' => $this->faker->randomNumber(2),
            'description' => $this->faker->realText(),
            'status' => $this->faker->numberBetween(0, 2),
        ];
    }
}
