<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Services\WalletService;

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
            'type.name' => 'string|required|unique:wallet_types,name',
            'type.min_balance' => 'numeric|required|gte:0',
            'type.monthly_interest_rate' => 'required|numeric|gte:0'
        ]);

        $wallet = $this->walletService->create($request);

        return successResponse('Wallet successfully created.', $wallet, 201);
    }

    public function update(Request $request, $id) {

        $request['id'] = $id;

        $wallet = $this->walletService->findById($id);

        $this->validate($request, [
            'id' => 'required|integer|exists:wallets,id',
            'name' => 'string|sometimes|unique:wallets,name,'.$wallet->type->id,
            'type' => 'array|sometimes',
            'type.name' => 'string|sometimes|unique:wallet_types,name,'.$wallet->id,
            'type.min_balance' => 'numeric|sometimes|gte:0',
            'type.monthly_interest_rate' => 'sometimes|numeric|gte:0'
        ]);

        $this->walletService->update($wallet, $request);

        return successResponse('Wallet successfully updated.', $wallet);
    }

    public function transfer(Request $request, $id) {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|exists:wallets,id',
            'wallet_address' => 'required|string|exists:wallets,address',
            'amount' => 'required|numeric|gt:0',
        ]);

        $senderWallet = $this->walletService->findById($id);
        $receiverWallet = $this->walletService->findByWalletAddress($request->wallet_address);

        $senderNewWalletBalance = $senderWallet->balance - $request->amount;
        $receiverNewWalletBalance = $receiverWallet->balance + $request->amount;

        if($request->amount <= $senderWallet->balance && $senderNewWalletBalance >= $senderWallet->type->min_balance){

            $this->walletService->transfer([
                'amount' => $request->amount,
                'sender_wallet' => $senderWallet,
                'receiver_wallet' => $receiverWallet,
                'sender_new_wallet_balance' => $senderNewWalletBalance,
                'receiver_new_wallet_balance' => $receiverNewWalletBalance,
            ]);

            return successResponse('Transfer Successful.', $senderWallet);
        }

        return errorResponse('Insufficient fund.', [], 422);

    }

    public function topup(Request $request, $id) {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|exists:wallets,id',
            'amount' => 'required|numeric|gt:0',
        ]);

        $wallet = $this->walletService->findById($id);
        $newWalletBalance = $wallet->balance + $request->amount;

        if($request->amount >= $wallet->type->min_balance){

            $this->walletService->topup($wallet, $request->amount, $newWalletBalance);

            return successResponse('Wallet successfully credited.', $wallet);
        }

        return errorResponse("Amount supplied is less than the minimum amount ({$wallet->type->min_balance}) required in this wallet.", [], 422);
    }

    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:wallets,id']);

        $wallet = $this->walletService->findById($id);

        return successResponse('Wallet info successfully retrieved.', $wallet);
    }
}
