<?php


namespace App\Services;

use App\Models\User;
use App\Helpers\WalletHelper;
use App\Models\WalletType;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class UserService {

    use WalletHelper;

    /**
     * @param $request
     * @return mixed
     */
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

    /**
     * @param User $user
     */
    private function createDefaultWallet(User $user) {
        $walletType = WalletType::create(['name' => 'Default']);
        $user->wallets()->create([
            'name' => $user->first_name,
            'wallet_type_id' => $walletType->id,
            'address' => $this->generateWalletAddress()
        ]);
    }

    /**
     * @return mixed
     */
    public function getAll() {
        return User::orderBy('id', 'desc')->get();
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findById(int $id) {
        return User::with(['wallets', 'transactions'])->find($id);
    }

    /**
     * @param string $plainPassword
     * @param string $encryptedPassword
     * @return bool
     */
    public function verifyPassword(string $plainPassword, string $encryptedPassword) {
        return Hash::check($plainPassword, $encryptedPassword);
    }

    /**
     * @return mixed
     */
    public function getTotal() {
        return $this->getAll()->count();
    }

}
