<?php

use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\RegisterController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function(){

    Route::group(['prefix' => 'auth'], function(){

        Route::post('register', [RegisterController::class, 'register']);

        Route::post('login', [LoginController::class, 'login']);

        Route::group(['middleware' => 'auth:sanctum'], function(){

            Route::post('logout', [LoginController::class, 'logout']);

        });

    });

    Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function(){

    });
});
