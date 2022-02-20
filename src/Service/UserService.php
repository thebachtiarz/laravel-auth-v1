<?php

namespace TheBachtiarz\Auth\Service;

use TheBachtiarz\Auth\Job\UserJob;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class UserService
{
    use DataResponse;

    /**
     * identifier
     *
     * @var string
     */
    private static string $identifier;

    /**
     * old password
     *
     * @var string
     */
    private static string $OldPassword;

    /**
     * new password
     *
     * @var string
     */
    private static string $newPassword;

    /**
     * validate old password
     *
     * @var boolean
     */
    private static bool $validateOldPassword = false;

    // ? Public Methods
    /**
     * change password
     *
     * @return array
     */
    public static function changePassword(): array
    {
        try {
            $_updatePassword = UserJob::setValidateWithOldPassword(self::$validateOldPassword);

            if (self::$validateOldPassword)
                $_updatePassword = $_updatePassword->setOldPassword(self::$OldPassword);

            $_updatePassword = $_updatePassword->setNewPassword(self::$newPassword)->changePassword();
            throw_if(!$_updatePassword['status'], 'Exception', $_updatePassword['message']);

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
     * @param string $identifier identifier
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
     * @param string $OldPassword old password
     * @return self
     */
    public static function setOldPassword(string $OldPassword): self
    {
        self::$OldPassword = $OldPassword;

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

    /**
     * Set validate old password
     *
     * @param boolean $validateOldPassword validate old password
     * @return self
     */
    public static function setValidateOldPassword(bool $validateOldPassword = false): self
    {
        self::$validateOldPassword = $validateOldPassword;

        return new self;
    }
}
