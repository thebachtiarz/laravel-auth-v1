<?php

namespace TheBachtiarz\Auth\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Auth\Job\AuthJob;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class AuthService
{
    use DataResponse;

    /**
     * identifier
     *
     * @var string
     */
    private static string $identifier;

    /**
     * password
     *
     * @var string
     */
    private static string $password;

    /**
     * revoke token
     *
     * @var boolean
     */
    private static bool $revokeToken = false;

    // ? Public Methods
    /**
     * get auth token
     *
     * @return array
     */
    public static function getToken(): array
    {
        try {
            $_getToken = AuthJob::setAuthIdentifier(self::$identifier)->setAuthPassword(self::$password)->loginToken();

            throw_if(!$_getToken['status'], 'Exception', $_getToken['message']);

            $_token = [
                'token' => $_getToken['data']['token']
            ];

            return self::responseData($_token, $_getToken['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * get auth session
     *
     * @return array
     */
    public static function getSession(): array
    {
        try {
            $_getSession = AuthJob::setAuthIdentifier(self::$identifier)->setAuthPassword(self::$password)->loginApps();

            throw_if(!$_getSession['status'], 'Exception', $_getSession['message']);

            return self::responseData($_getSession['data'], $_getSession['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * delete auth token
     *
     * @return array
     */
    public function deleteToken(): array
    {
        try {
            throw_if(!Auth::check(), 'Exception', "There is no session");

            $_deleteToken = AuthJob::setUser(Auth::user())->revokeTokens(self::$revokeToken)->logoutToken();

            throw_if(!$_deleteToken['status'], 'Exception', $_deleteToken['message']);

            return self::responseData($_deleteToken['data'], $_deleteToken['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * delete auth session
     *
     * @return array
     */
    public function deleteSession(): array
    {
        try {
            throw_if(!Auth::check(), 'Exception', "There is no session");

            $_deleteSession = AuthJob::setUser(Auth::user())->logoutApps();

            throw_if(!$_deleteSession['status'], 'Exception', $_deleteSession['message']);

            return self::responseData($_deleteSession['data'], $_deleteSession['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    // ? Private Methods

    // ? Setter Modules
    /**
     * Set identifier
     *
     * @param string $identifier identifier
     * @return self
     */
    public static function setIdentifier(string $identifier): self
    {
        self::$identifier = $identifier;

        return new self;
    }

    /**
     * Set password
     *
     * @param string $password password
     * @return self
     */
    public static function setPassword(string $password): self
    {
        self::$password = $password;

        return new self;
    }

    /**
     * Set revoke token
     *
     * @param boolean $revokeToken revoke token
     * @return self
     */
    public static function setRevokeToken(bool $revokeToken = false): self
    {
        self::$revokeToken = $revokeToken;

        return new self;
    }
}
