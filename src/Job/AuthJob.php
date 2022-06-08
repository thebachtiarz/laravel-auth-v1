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
     * Auth identifier
     *
     * @var string
     */
    protected static string $authIdentifier;

    /**
     * Auth password
     *
     * @var string
     */
    protected static string $authPassword;

    /**
     * Auth revoke token
     *
     * @var boolean
     */
    protected static bool $authRevokeToken = false;

    // ? Public Methods
    /**
     * Login token process.
     * For rest api process.
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
     * Login apps process.
     * For web app process.
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
     * Logout token process.
     * For rest api process.
     *
     * @return array
     */
    public static function logoutToken(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!Auth::check(), 'Exception', "There is no session");

            $_token = self::$user;

            if (self::$authRevokeToken) {
                /**
                 * revoke all logins
                 */
                $_token = $_token->tokens();
                $message = "Successfully Revoke All Logins";
            } else {
                /**
                 * revoke only current login
                 */
                $_token = $_token->currentAccessToken();
                $message = "Successfully Logout";
            }

            $_token = $_token->delete();

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
     * Logout apps process.
     * For web app process.
     *
     * @return array
     */
    public static function logoutApps(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!Auth::check(), 'Exception', "There is no session");

            Auth::logout();

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
     * Login using credential
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
     * Token creator resolver
     *
     * @return array
     */
    private static function tokenCreateResolver(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            if (tbauthconfig('child_model_user_class')) {
                throw_if(
                    !class_exists(tbauthconfig('child_model_user_class')),
                    'Exception',
                    sprintf("Class '%s' is not defined", tbauthconfig('child_model_user_class'))
                );
            }

            $_createToken = self::$user->createToken(time() . "|" . Str::random(8));

            throw_if(!$_createToken, 'Exception', "Failed to create token");

            $_createToken = self::tokenableModify($_createToken);

            $result['status'] = true;
            $result['data'] = $_createToken->plainTextToken;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * Tokenable modifier for apply child user class
     *
     * @param NewAccessToken $newAccessToken
     * @return NewAccessToken
     */
    private static function tokenableModify(NewAccessToken $newAccessToken): NewAccessToken
    {
        try {
            $_accessModify = PersonalAccessToken::find($newAccessToken->accessToken->id);

            throw_if(!$_accessModify, 'Exception', "Token not found");

            $_accessModify->tokenable_type = tbauthconfig('child_model_user_class') ?: User::class;

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
