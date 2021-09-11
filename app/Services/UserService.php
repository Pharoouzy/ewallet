<?php


namespace App\Services;

use App\Models\User;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Hash;

class UserService {

    use WalletHelper;

    public function createUser($request){

        $request['password'] = Hash::make($request->password);

        $user = User::create($request->only([
            'first_name',
            'last_name',
            'email',
            'password',
        ]));

        $this->createDefaultWallet($user);

        return $user;
    }

    private function createDefaultWallet($user, $walletTypeId = 1) {
        $user->wallets()->create([
            'name' => $user->first_name,
            'wallet_type_id' => $walletTypeId,
            'address' => $this->generateWalletAddress()
        ]);
    }

}