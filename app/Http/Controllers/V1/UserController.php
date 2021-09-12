<?php

namespace App\Http\Controllers\V1;

use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers\V1
 */
class UserController extends Controller {

    /**
     * @var UserService
     */
    public $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $users = $this->userService->getAll();

        return successResponse('Users successfully retrieved', $users);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:users,id']);

        $user = $this->userService->findById($id);

        return successResponse('User info successfully retrieved', $user);
    }
}
