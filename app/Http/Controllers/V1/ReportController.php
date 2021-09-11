<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller {

    public function index() {

        $data = [
            'user_count' => 1,
            'wallet_count' => 1,
            'total_wallet_balance' => 1,
            'transaction_volume' => 1,
        ];

        return successResponse('Report successfully retrieved', $data);
    }
}
