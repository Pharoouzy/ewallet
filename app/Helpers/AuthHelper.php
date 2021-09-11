<?php

namespace App\Helpers;

trait AuthHelper {

    public function generateToken($user){

        $tokenObject =  $user->createToken(config('app.name'));

        return [
            'token' => $tokenObject->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }

}
