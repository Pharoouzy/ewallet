<?php

namespace Database\Seeders;

use App\Models\WalletType;
use Illuminate\Database\Seeder;

class WalletTypeSeeder extends Seeder {

    private $walletTypes = [
        [
            'name' => 'Default',
            'min_balance' => 0.00,
            'monthly_interest_rate' => 8.00,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        WalletType::insert($this->walletTypes);
    }
}
