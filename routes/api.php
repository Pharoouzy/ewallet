<?php

use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\RegisterController;

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

        Route::post('register', [RegisterController::class, 'register'])->name('auth.register');

        Route::post('login', [LoginController::class, 'login'])->name('auth.login');

        Route::group(['middleware' => 'auth:sanctum'], function(){

            Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

        });

    });

    Route::group(['middleware' => 'auth:sanctum'], function(){

        Route::group(['prefix' => 'users'], function(){

            Route::get('', [UserController::class, 'index'])->name('users.index');

            Route::get('{id}', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');

        });

        Route::group(['prefix' => 'wallets'], function(){

            Route::get('', [WalletController::class, 'index'])->name('wallets.index');

            Route::post('', [WalletController::class, 'store'])->name('wallets.store');

            Route::get('{id}', [WalletController::class, 'show'])->name('wallets.show')->where('id', '[0-9]+');

            Route::post('{id}/transfer', [WalletController::class, 'transfer'])->name('wallets.transfer')->where('id', '[0-9]+');

            Route::post('{id}/topup', [WalletController::class, 'topup'])->name('wallets.topup')->where('id', '[0-9]+');

        });

        Route::group(['prefix' => 'reports'], function() {

            Route::get('', [ReportController::class, 'index'])->name('reports.index');
        });

    });
});
