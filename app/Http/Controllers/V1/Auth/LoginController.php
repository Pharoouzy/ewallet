<?php

namespace App\Http\Controllers\V1\Auth;

use App\Helpers\AuthHelper;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;

/**
 * Class LoginController
 * @package App\Http\Controllers\V1\Auth
 */
class LoginController extends Controller {
    use AuthHelper;

    /**
     * @var UserService
     */
    public $userService;

    /**
     * LoginController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return successResponse('User logged out from API successfully.');
    }

}
