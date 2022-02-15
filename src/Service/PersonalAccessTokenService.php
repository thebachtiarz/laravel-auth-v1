<?php

namespace TheBachtiarz\Auth\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Auth\Job\PersonalAccessTokenJob;

class PersonalAccessTokenService
{
    //

    // ? Public Methods
    /**
     * get own user list tokens
     *
     * @return object|null
     */
    public static function getMyTokens(): array
    {
        try {
            $auth = Auth::user();

            $_tokens = PersonalAccessTokenJob::setUser($auth)->getTokens(true);

            throw_if(!$_tokens['status'], 'Exception', $_tokens['message']);

            return $_tokens['data'];
        } catch (\Throwable $th) {
            return null;
        }
    }

    // ? Private Methods

    // ? Setter Modules
}
