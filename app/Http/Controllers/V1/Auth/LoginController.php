<?php

namespace App\Http\Controllers\V1\Auth;

use App\Helpers\AuthHelper;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;

class LoginController extends Controller {
    use AuthHelper;

    public $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function login(Request $request) {

        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = $this->userService->findByEmail($request->email);

        if ($user && $this->userService->verifyPassword($request->password, $user->password)) {
            return successResponse('User successfully authenticated.', $this->generateToken($user));
        }

        return errorResponse('Unauthorized credentials.', [], 401);
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return successResponse('User logged out from API successfully.');
    }

}
