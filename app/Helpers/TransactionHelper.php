<?php

namespace App\Helpers;

use App\Models\Transaction;
use Illuminate\Support\Str;

trait TransactionHelper {

    public function generateTransactionReference() {

        $reference =  Str::random(10);

        if(Transaction::where('reference', $reference)->exists()) {
            return $this->generateTransactionReference();
        }

        return $reference;
    }
}
