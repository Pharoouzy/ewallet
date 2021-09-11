<?php

namespace App\Helpers;

use App\Models\Wallet;
use Illuminate\Support\Str;

trait WalletHelper {

    public function generateWalletAddress(){

        $address =  Str::random(60);

        if(Wallet::where('address', $address)->exists()){
            return $this->generateWalletAddress();
        }

        return $address;
    }
}
