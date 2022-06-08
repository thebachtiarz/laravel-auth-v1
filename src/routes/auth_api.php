<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Auth\Http\Controllers\API\{AuthController, UserController};

/**
 * Route group auth
 * Route :: base_url/{{app_prefix}}/auth/---
 */
Route::prefix('auth')->group(function () {

    /**
     * Route auth token
     * Route :: base_url/{{app_prefix}}/auth/token/---
     */
    Route::prefix('token')->controller(AuthController::class)->group(function () {

        /**
         * Route for get api token
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/auth/token/login
         */
        Route::post('login', 'login');

        /**
         * Route for delete api token
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/auth/token/logout
         */
        Route::post('logout', 'logout');

        /**
         * Route for get all own token
         * Method :: GET
         * Route :: base_url/{{app_prefix}}/auth/token/tokens
         */
        Route::get('tokens', 'tokens');

        /**
         * Route for delete own api token by token name
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/auth/token/delete
         */
        Route::post('delete', 'deleteToken');

        /**
         * Route for revoke all own api token
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/auth/token/revoke
         */
        Route::post('revoke', 'revokeTokens');
    });

    Route::prefix('user')->controller(UserController::class)->group(function () {

        /**
         * Route for password update auth
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/auth/user/password-update-auth
         */
        Route::post('password-update-auth', 'passwordUpdateAuth');

        /**
         * Route for password update guest
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/user/password-update-guest
         */
        Route::post('password-update-guest', 'passwordUpdateGuest');
    });
});
