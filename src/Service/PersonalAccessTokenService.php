<?php

namespace TheBachtiarz\Auth\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Auth\Job\PersonalAccessTokenJob;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class PersonalAccessTokenService
{
    use DataResponse;

    /**
     * Token name
     *
     * @var string
     */
    private static string $tokenName;

    // ? Public Methods
    /**
     * Get own current user tokens
     *
     * @return array
     */
    public static function getMyTokens(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_tokens = PersonalAccessTokenJob::setUser(Auth::user())->getTokens(true);

            throw_if(!$_tokens['status'], 'Exception', $_tokens['message']);

            return self::responseData($_tokens['data'], $_tokens['message'], 200);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Find own current user token by token name
     *
     * @return array
     */
    public static function findMyToken(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_token = PersonalAccessTokenJob::setUser(Auth::user())->setTokenName(self::$tokenName)->findToken(true);

            throw_if(!$_token['status'], 'Exception', $_token['message']);

            return self::responseData($_token['data'], $_token['message'], 200);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Delete own current user token by token name
     *
     * @return array
     */
    public static function deleteMyToken(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_token = PersonalAccessTokenJob::setUser(Auth::user())->setTokenName(self::$tokenName)->deleteToken(true);

            throw_if(!$_token['status'], 'Exception', $_token['message']);

            return self::responseData($_token['data'], $_token['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Delete all own current user tokens
     *
     * @return array
     */
    public static function revokeMyTokens(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_tokens = PersonalAccessTokenJob::setUser(Auth::user())->revokeTokens(true);

            throw_if(!$_tokens['status'], 'Exception', $_tokens['message']);

            return self::responseData($_tokens['data'], $_tokens['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    // ? Private Methods

    // ? Setter Modules
    /**
     * Set token name
     *
     * @param string $tokenName token name
     * @return self
     */
    public static function setTokenName(string $tokenName): self
    {
        self::$tokenName = $tokenName;

        return new self;
    }
}
