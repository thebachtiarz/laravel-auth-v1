<?php

namespace TheBachtiarz\Auth\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Auth\Job\UserJob;
use TheBachtiarz\Auth\Model\User;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class UserService
{
    use DataResponse;

    /**
     * identifier [email, username]
     *
     * @var string
     */
    private static string $identifier;

    /**
     * Old password
     *
     * @var string
     */
    private static string $oldPassword;

    /**
     * New password
     *
     * @var string
     */
    private static string $newPassword;

    /**
     * Validate old password
     *
     * @var boolean
     */
    private static bool $validateOldPassword = false;

    // ? Public Methods
    /**
     * Change password
     *
     * @return array
     */
    public static function changePassword(): array
    {
        try {
            if (Auth::hasUser()) {
                $_user = Auth::user();
            } else {
                $_user = User::getByIdentifier(self::$identifier)->first();

                throw_if(!$_user, 'Exception', "User not found");
            }

            $_updatePassword = UserJob::setUser($_user);

            if (self::$validateOldPassword)
                $_updatePassword = $_updatePassword->setOldPassword(self::$oldPassword);

            $_updatePassword = $_updatePassword->setNewPassword(self::$newPassword)->changePassword();

            throw_if(!$_updatePassword['status'], 'Exception', $_updatePassword['message']);

            Auth::logout();

            return self::responseData([], $_updatePassword['message'], 201);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    // ? Private Methods

    // ? Setter Modules
    /**
     * Set identifier
     *
     * @param string $identifier identifier [email, username]
     * @return self
     */
    public static function setIdentifier(string $identifier): self
    {
        self::$identifier = $identifier;

        return new self;
    }

    /**
     * Set old password
     *
     * @param string $oldPassword old password
     * @return self
     */
    public static function setOldPassword(string $oldPassword): self
    {
        self::$oldPassword = $oldPassword;
        self::$validateOldPassword = true;

        return new self;
    }

    /**
     * Set new password
     *
     * @param string $newPassword new password
     * @return self
     */
    public static function setNewPassword(string $newPassword): self
    {
        self::$newPassword = $newPassword;

        return new self;
    }
}
