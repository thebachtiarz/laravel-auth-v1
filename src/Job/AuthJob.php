<?php

namespace TheBachtiarz\Auth\Job;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Auth\Model\{PersonalAccessToken, User};
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

class AuthJob
{
    use ErrorLogTrait;

    /**
     * Model User data
     *
     * @var User
     */
    protected static User $user;

    /**
     * Facade Auth data
     *
     * @var Auth
     */
    protected static Auth $auth;

    /**
     * auth identifier
     *
     * @var string
     */
    protected static string $authIdentifier;

    /**
     * auth password
     *
     * @var string
     */
    protected static string $authPassword;

    /**
     * auth revoke token
     *
     * @var boolean
     */
    protected static bool $authRevokeToken = false;

    // ? Public Methods
    /**
     * login token process.
     * for rest api process.
     *
     * @return array
     */
    public static function loginToken(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_loginAttempt = self::loginUsingCredential();

            throw_if(!$_loginAttempt['status'], 'Exception', $_loginAttempt['message']);

            $_createToken = self::tokenCreateResolver();

            throw_if(!$_createToken['status'], 'Exception', $_createToken['message']);

            $_resultData = [
                'user' => self::$user,
                'token' => $_createToken['data']
            ];

            $result['data'] = $_resultData;
            $result['status'] = true;
            $result['message'] = "User logged in successfully";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * login apps process.
     * for web app process.
     *
     * @return array
     */
    public static function loginApps(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_loginAttempt = self::loginUsingCredential();

            throw_if(!$_loginAttempt['status'], 'Exception', $_loginAttempt['message']);

            $result['status'] = true;
            $result['message'] = "User logged in successfully";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * logout token process.
     * for rest api process.
     *
     * @return array
     */
    public static function logoutToken(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!self::$auth, 'Exception', "There is no session");

            if (self::$authRevokeToken) {
                /**
                 * revoke all logins
                 */
                self::$user->tokens();
                $message = "Successfully Revoke All Logins";
            } else {
                /**
                 * revoke only current login
                 */
                self::$user->currentAccessToken();
                $message = "Successfully Logout";
            }

            self::$user->delete();

            $result['status'] = true;
            $result['message'] = $message;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * logout apps process.
     * for web app process.
     *
     * @return array
     */
    public static function logoutApps(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!self::$auth, 'Exception', "There is no session");

            self::$auth::logout();

            $result['status'] = true;
            $result['message'] = "Successfully logout";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    // ? Private Methods
    /**
     * login using credential
     *
     * @return array
     */
    private static function loginUsingCredential(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_auth = Auth::attempt([
                tbauthconfig('user_auth_identity_method') => self::$authIdentifier,
                'password' => self::$authPassword
            ], true);

            throw_if(!$_auth, 'Exception', "Failed to attempt credential");

            self::setUser(User::find(Auth::user()->id));

            $result['status'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * token creator resolver
     *
     * @return array
     */
    private static function tokenCreateResolver(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_createToken = self::$user->createToken(date("Ymd") . "|" . Str::random(12));

            throw_if(!$_createToken, 'Exception', "Failed to create token");

            $_createToken = self::tokenableMutatorModify($_createToken);

            $result['status'] = true;
            $result['data'] = $_createToken->plainTextToken;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * tokenable modifier for apply child user class
     *
     * @param NewAccessToken $newAccessToken
     * @return NewAccessToken
     */
    private static function tokenableMutatorModify(NewAccessToken $newAccessToken): NewAccessToken
    {
        try {
            $_accessModify = PersonalAccessToken::find($newAccessToken->accessToken->id);

            throw_if(!$_accessModify, 'Exception', "Token not found");

            $_accessModify->tokenable_type = tbauthconfig('child_model_user_class');

            $_accessModify->save();

            $newAccessToken->accessToken = $_accessModify;
        } catch (\Throwable $th) {
        } finally {
            return $newAccessToken;
        }
    }

    // ? Setter Modules
    /**
     * Set model User data
     *
     * @param User $user Model User data
     * @return self
     */
    public static function setUser(User $user): self
    {
        self::$user = $user;

        return new self;
    }

    /**
     * Set facade Auth data
     *
     * @param Auth $auth Facade Auth data
     * @return self
     */
    public static function setAuth(Auth $auth): self
    {
        self::$auth = $auth;

        return new self;
    }

    /**
     * Set auth identifier
     *
     * @param string $authIdentifier auth identifier
     * @return self
     */
    public static function setAuthIdentifier(string $authIdentifier): self
    {
        self::$authIdentifier = $authIdentifier;

        return new self;
    }

    /**
     * Set auth password
     *
     * @param string $authPassword auth password
     * @return self
     */
    public static function setAuthPassword(string $authPassword): self
    {
        self::$authPassword = $authPassword;

        return new self;
    }

    /**
     * Revoke all token
     *
     * @param boolean $authRevokeToken auth revoke token
     * @return self
     */
    public static function revokeTokens(bool $authRevokeToken = true): self
    {
        self::$authRevokeToken = $authRevokeToken;

        return new self;
    }
}
