<?php

namespace App\Http\Controllers\V1\Auth;

use App\Models\User;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\V1\Controller;

class LoginController extends Controller {
    use AuthHelper;

    public function login(Request $request) {

        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return successResponse('User successfully authenticated.', $this->generateToken($user));
        }

        return errorResponse('Unauthorized credentials.', [], 401);
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return successResponse('User logged out from API successfully.');
    }

}
