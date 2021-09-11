<?php

namespace Database\Factories;

use App\Models\WalletType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WalletType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->userName(),
            'min_balance' => $this->faker->randomNumber(2),
            'monthly_interest_rate' => $this->faker->randomDigit(),
        ];
    }
}
