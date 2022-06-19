<?php

namespace TheBachtiarz\Auth\Http\Controllers\API;

use Illuminate\Http\Response;
use TheBachtiarz\Auth\Http\Controllers\Controller;
use TheBachtiarz\Auth\Http\Requests\{LoginApiRequest, LogoutApiRequest, TokenNameApiRequest};
use TheBachtiarz\Auth\Service\{AuthService, PersonalAccessTokenService};
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class AuthController extends Controller
{
    use DataResponse;

    // ? Public Methods
    /**
     * Login get token.
     * Params: email/username, password.
     *
     * @param LoginApiRequest $request
     * @return Response
     */
    public function login(LoginApiRequest $request)
    {
        $_identifier = tbauthconfig('user_auth_identity_method') === 'email' ? $request->email : $request->username;

        $_process = AuthService::setIdentifier($_identifier)->setPassword($request->password)->getToken();

        return self::responseApiRest($_process);
    }

    /**
     * Logout delete token.
     * Params: revoke[false].
     *
     * @param LogoutApiRequest $request
     * @return Response
     */
    public function logout(LogoutApiRequest $request)
    {
        $_process = AuthService::setRevokeToken($request->revoke)->deleteToken();

        return self::responseApiRest($_process);
    }

    /**
     * Get tokens
     *
     * @return Response
     */
    public function tokens()
    {
        $_process = PersonalAccessTokenService::getMyTokens();

        return self::responseApiRest($_process);
    }

    /**
     * Delete own token by token name.
     * Params: token_name
     *
     * @param TokenNameApiRequest $request
     * @return Response
     */
    public function deleteToken(TokenNameApiRequest $request)
    {
        $_process = PersonalAccessTokenService::setTokenName($request->token_name)->deleteMyToken();

        return self::responseApiRest($_process);
    }

    /**
     * Delete all own token
     *
     * @return Response
     */
    public function revokeTokens()
    {
        $_process = PersonalAccessTokenService::revokeMyTokens();

        return self::responseApiRest($_process);
    }

    // ? Private Methods

    // ? Setter Modules
}
