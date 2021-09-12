<?php


namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletType;
use App\Helpers\WalletHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class WalletService
 * @package App\Services
 */
class WalletService {

    use WalletHelper, TransactionHelper;

    /**
     * @param $request
     * @return mixed
     */
    public function create($request){

        $walletType = $this->createWalletType($request->type);
        $wallet = $walletType->wallets()->create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'address' => $this->generateWalletAddress(),
        ]);

        return Wallet::find($wallet->id);
    }

    /**
     * @return mixed
     */
    public function getAll() {
        return Wallet::orderBy('id', 'desc')->get();
    }

    /**
     * @return mixed
     */
    public function getTotal() {
        return $this->getAll()->count();
    }

    /**
     * @return mixed
     */
    public function getTotalWalletsBalance() {
        return $this->getAll()->sum('balance');
    }


    /**
     * @param $data
     * @return mixed
     */
    private function createWalletType($data) {
        return WalletType::create([
            'name' => $data['name'],
            'min_balance' => $data['min_balance'],
            'monthly_interest_rate' => $data['monthly_interest_rate'],
        ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findById(int $id) {
        return Wallet::with(['user', 'type', 'transactions'])->find($id);
    }

    /**
     * @param string $address
     * @return mixed
     */
    public function findByWalletAddress(string $address) {
        return Wallet::where('address', $address)->first();
    }

    /**
     * @param $wallet
     * @param float $amount
     * @param float $newWalletBalance
     */
    public function topup($wallet, float $amount, float $newWalletBalance) {

        DB::transaction(function () use ($wallet, $amount, $newWalletBalance) {

            $wallet->increment('balance', $amount);

            $this->createWalletTransaction($wallet, $amount, $newWalletBalance, null, 1, true);

        }, 5);
    }

    /**
     * @param array $data
     */
    public function transfer(array $data) {

        DB::transaction(function () use ($data){

            $senderWallet = $data['sender_wallet'];
            $receiverWallet = $data['receiver_wallet'];
            $senderWallet->decrement('balance', $data['amount']);
            $receiverWallet->increment('balance', $data['amount']);

            $this->createWalletTransaction(
                $senderWallet,
                $data['amount'],
                $data['sender_new_wallet_balance'],
                $receiverWallet,
                0
            );

            $this->createWalletTransaction(
                $receiverWallet,
                $data['amount'],
                $data['receiver_new_wallet_balance'],
                $senderWallet,
            );

        }, 5);
    }

    /**
     * @param Wallet $firstWallet
     * @param float $amount
     * @param float $newBalance
     * @param Wallet|null $secondWallet
     * @param int $type
     * @param bool $isTopup
     */
    private function createWalletTransaction(
        Wallet $firstWallet,
        float $amount,
        float $newBalance,
        Wallet $secondWallet = null,
        int $type = 1,
        bool $isTopup = false
    ){

        $formattedAmount = number_format($amount, 2);

        if($isTopup){
            $description = "Topup of NGN{$formattedAmount} to {$firstWallet->name} has been processed successfully.";
        } else {
            $description = $type === 1 ?
                "Credit of NGN{$formattedAmount} from {$secondWallet->name} has been processed successfully." :
                "Transfer of NGN{$formattedAmount} to {$secondWallet->name} has been processed successfully.";
        }


        $firstWallet->transactions()->create([
            'reference' => $this->generateTransactionReference(),
            'user_id' => $firstWallet->user_id,
            'amount' => $amount,
            'new_balance' => $newBalance,
            'description' => $description,
            'type' => $type,
            'status' => 1,
        ]);
    }

    public function update($wallet, $request){
        $wallet->update(['name' => $request->name]);
        $wallet->type()->update([
            'name' => $request->type['name'],
            'min_balance' => $request->type['min_balance'],
            'monthly_interest_rate' => $request->type['monthly_interest_rate'],
        ]);

        return $wallet;
    }
}
