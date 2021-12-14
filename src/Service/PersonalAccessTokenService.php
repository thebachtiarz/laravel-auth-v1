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
    public static function getMyTokens(): ?object
    {
        try {
            $auth = Auth::user();

            $tokens = PersonalAccessTokenJob::setUser($auth)->get();

            throw_if(!count($tokens), 'Exception', "Token not found");

            return $tokens->map->simpleListMap();
        } catch (\Throwable $th) {
            return null;
        }
    }

    // ? Private Methods

    // ? Setter Modules
}
