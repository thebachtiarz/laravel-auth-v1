<?php

namespace TheBachtiarz\Auth\Http\Controllers\API;

use Illuminate\Http\Response;
use TheBachtiarz\Auth\Controllers\Controller;
use TheBachtiarz\Auth\Http\Requests\{UpdatePasswordAuthRequest, UpdatePasswordGuestRequest};
use TheBachtiarz\Auth\Service\UserService;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class UserController extends Controller
{
    use DataResponse;

    // ? Public Methods
    /**
     * Update password auth.
     * Params: password_old, password.
     *
     * @param UpdatePasswordAuthRequest $request
     * @return Response
     */
    public function passwordUpdateAuth(UpdatePasswordAuthRequest $request)
    {
        $_process = UserService::setOldPassword($request->password_old)
            ->setNewPassword($request->password)
            ->changePassword();

        return self::responseApiRest($_process);
    }

    /**
     * Update password guest.
     * Params: email/username, password.
     *
     * @param UpdatePasswordGuestRequest $request
     * @return Response
     */
    public function passwordUpdateGuest(UpdatePasswordGuestRequest $request)
    {
        $_identifier = tbauthconfig('user_auth_identity_method') === 'email' ? $request->email : $request->username;

        $_process = UserService::setIdentifier($_identifier)
            ->setNewPassword($request->password)
            ->changePassword();

        return self::responseApiRest($_process);
    }

    // ? Private Methods

    // ? Setter Modules
}
