<?php

namespace App\Http\Controllers\V1;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller {

    public function index() {
        $wallets = Wallet::orderBy('id')->get();

        return successResponse('Wallets successfully retrieved', $wallets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    public function transfer(Request $request) {
        //
    }

    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:users,id']);

        $wallet = Wallet::with(['user', 'type', 'transactions'])->find($id);

        return successResponse('Wallet info successfully retrieved', $wallet);
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
