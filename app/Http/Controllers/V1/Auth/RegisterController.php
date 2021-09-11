<?php

namespace App\Http\Controllers\V1\Auth;

use App\Models\User;
use App\Helpers\AuthHelper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\V1\Controller;

/**
 * Class RegisterController
 * @package App\Http\Controllers\V1\Auth
 */
class RegisterController extends Controller {

    use AuthHelper;

    public $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $this->validate($request, [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = $this->userService->createUser($request);
        $data = $this->generateToken($user);

        return successResponse('Account successfully created.', $data, 201);
    }
}
