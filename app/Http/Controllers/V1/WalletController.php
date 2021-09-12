<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller {

    public $walletService;

    public function __construct(WalletService $walletService) {
        $this->walletService = $walletService;
    }

    public function index() {
        $wallets = $this->walletService->getAll();

        return successResponse('Wallets successfully retrieved', $wallets);
    }


    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'string|required|unique:wallets,name',
            'type' => 'array|required',
            'type.min_balance' => 'numeric|required|gte:0',
            'type.monthly_interest_rate' => 'required|numeric|gte:0'
        ]);

        $wallet = $this->walletService->create($request);

        return successResponse('Wallet successfully created.', $wallet, 201);
    }

    public function transfer(Request $request, $id) {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|exists:wallets,id',
            'wallet_address' => 'required|integer|exists:wallets,address',
            'amount' => 'required|numeric|gt:0',
        ]);

        $senderWallet = $this->walletService->findById($id);
        $receiverWallet = $this->walletService->findByWalletAddress($request->wallet_address);

        $senderNewWalletBalance = $senderWallet->balance - $request->amount;
        $receiverNewWalletBalance = $receiverWallet->balance + $request->amount;

        if($request->amount >= $senderWallet->balance && $senderNewWalletBalance <= $senderWallet->type->min_balance){

            $this->walletService->transfer([
                'amount' => $request->amount,
                'sender_wallet' => $senderWallet,
                'receiver_wallet' => $receiverWallet,
                'sender_new_wallet_balance' => $senderNewWalletBalance,
                'receiver_new_wallet_balance' => $receiverNewWalletBalance,
            ]);

            return successResponse('Transfer Successful.', $senderWallet);
        }

        return errorResponse('Insufficient funds.', [], 422);
        //TODO: check wallet balance and see if min_balance check is good to go
        //TODO: if sufficient, credit receiver wallet and debit sender wallet balance (send email accordingly),
        //TODO: display message of not sufficient

    }

    public function topup(Request $request, $id) {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|exists:wallets,id',
            'amount' => 'required|numeric|gt:0',
        ]);

        $wallet = $this->walletService->findById($id);
        $newWalletBalance = $wallet->balance + $request->amount;
        //TODO: check wallet balance is empty and if the topup amount is gte min_balance

        $this->walletService->topup($wallet, $request->amount, $newWalletBalance);

        return successResponse('Wallet successfully credited.', $wallet);

    }

    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:wallets,id']);

        $wallet = $this->walletService->findById($id);

        return successResponse('Wallet info successfully retrieved.', $wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
