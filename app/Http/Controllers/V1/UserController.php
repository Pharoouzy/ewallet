<?php

namespace App\Http\Controllers\V1;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {

    public $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index() {
        $users = $this->userService->getAll();

        return successResponse('Users successfully retrieved', $users);
    }

    public function show(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, ['id' => 'required|integer|exists:users,id']);

        $user = $this->userService->findById($id);

        return successResponse('User info successfully retrieved', $user);
    }

    public function update(Request $request, $id) {
        //
    }
}
