<?php

namespace TheBachtiarz\Auth\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Auth\Job\PersonalAccessTokenJob;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class PersonalAccessTokenService
{
    use DataResponse;

    /**
     * token name
     *
     * @var string
     */
    private static string $tokenName;

    // ? Public Methods
    /**
     * get own current user tokens
     *
     * @return array
     */
    public static function getMyTokens(): array
    {
        try {
            $_authUser = Auth::user();

            throw_if(!$_authUser, 'Exception', "There is no session");

            $_tokens = PersonalAccessTokenJob::setUser($_authUser)->getTokens(true);

            throw_if(!$_tokens['status'], 'Exception', $_tokens['message']);

            return self::responseData($_tokens['data'], $_tokens['message'], 200);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * find own current user token by token name
     *
     * @return array
     */
    public static function findMyToken(): array
    {
        try {
            $_authUser = Auth::user();

            throw_if(!$_authUser, 'Exception', "There is no session");

            $_token = PersonalAccessTokenJob::setUser($_authUser)->setTokenName(self::$tokenName)->findToken(true);

            throw_if(!$_token['status'], 'Exception', $_token['message']);

            return self::responseData($_token['data'], $_token['message'], 200);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * delete own current user token by token name
     *
     * @return array
     */
    public static function deleteMyToken(): array
    {
        try {
            $_authUser = Auth::user();

            throw_if(!$_authUser, 'Exception', "There is no session");

            $_token = PersonalAccessTokenJob::setUser($_authUser)->setTokenName(self::$tokenName)->deleteToken(true);

            throw_if(!$_token['status'], 'Exception', $_token['message']);

            return self::responseData($_token['data'], $_token['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * delete all own current user tokens
     *
     * @return array
     */
    public static function revokeMyTokens(): array
    {
        try {
            $_authUser = Auth::user();

            throw_if(!$_authUser, 'Exception', "There is no session");

            $_tokens = PersonalAccessTokenJob::setUser($_authUser)->revokeTokens(true);

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
