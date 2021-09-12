<?php


namespace App\Services;

use App\Models\User;
use App\Helpers\WalletHelper;
use Illuminate\Support\Facades\Hash;

class UserService {

    use WalletHelper;

    public function create($request){

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

    private function createDefaultWallet(User $user, int $walletTypeId = 1) {
        $user->wallets()->create([
            'name' => $user->first_name,
            'wallet_type_id' => $walletTypeId,
            'address' => $this->generateWalletAddress()
        ]);
    }

    public function getAll() {
        return User::orderBy('id', 'desc')->get();
    }

    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }

    public function findById(int $id) {
        return User::with(['wallets', 'transactions'])->find($id);
    }

    public function verifyPassword(string $plainPassword, string $encryptedPassword) {
        return Hash::check($plainPassword, $encryptedPassword);
    }

}
