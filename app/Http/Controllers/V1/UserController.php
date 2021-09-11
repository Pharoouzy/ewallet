<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index() {
        $users = User::orderBy('id')->get();

        return successResponse('Users successfully retrieved', $users);
    }

    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:users,id']);

        $user = User::with(['wallets', 'transactions'])->find($id);

        return successResponse('User info successfully retrieved', $user);
    }

    public function update(Request $request, $id) {
        //
    }
}
