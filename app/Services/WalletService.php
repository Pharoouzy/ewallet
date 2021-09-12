<?php


namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletType;
use App\Helpers\WalletHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\DB;

class WalletService {

    use WalletHelper, TransactionHelper;

    public function create($request){

        $walletType = $this->createWalletType($request->type);
        $wallet = $walletType->wallets()->create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'address' => $this->generateWalletAddress(),
        ]);

        return $wallet;
    }

    private function createWalletType($data) {
        return WalletType::create([
            'name' => $data->name,
            'min_balance' => $data->min_balance,
            'monthly_interest_rate' => $data->monthly_interest_rate,
        ]);
    }

    public function getAll() {
        return Wallet::orderBy('id')->get();
    }

    public function findById(int $id) {
        return Wallet::with(['user', 'type', 'transactions'])->find($id);
    }

    public function findByWalletAddress(string $address) {
        return Wallet::where('address', $address)->first();
    }

    public function topup($wallet, float $amount, float $newWalletBalance) {

        DB::transaction(function () use ($wallet, $amount, $newWalletBalance) {

            $wallet->increment('balance', $amount);

            $this->createWalletTransaction($wallet, $amount, $newWalletBalance);

        }, 5);
    }

    public function transfer(array $data) {

        DB::transaction(function () use ($data){

            $senderWallet = $data['senderWallet'];
            $receiverWallet = $data['receiverWallet'];
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
                $senderWallet,
                $data['amount'],
                $data['receiver_new_wallet_balance'],
                $receiverWallet,
            );

        }, 5);
    }

    private function createWalletTransaction(
        Wallet $senderWallet,
        float $amount,
        float $newBalance,
        Wallet $receiverWallet = null,
        int $type = 1,
        bool $isTopup = false,
    ){

        $formattedAmount = number_format($amount, 2);

        if($isTopup){
            $description = "Topup of NGN{$formattedAmount} to {$senderWallet->name} has been processed successfully.";
        } else {
            $description = $type === 1 ?
                "Credit of NGN{$formattedAmount} from {$senderWallet->name} has been processed successfully." :
                "Transfer of NGN{$formattedAmount} to {$receiverWallet->name} has been processed successfully.";
        }


        $senderWallet->transactions()->create([
            'reference' => $this->generateTransactionReference(),
            'user_id' => $senderWallet->user_id,
            'amount' => $amount,
            'new_balance' => $newBalance,
            'description' => $description,
            'type' => $type,
            'status' => 1,
        ]);
    }
}
