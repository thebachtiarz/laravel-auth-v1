<?php

namespace TheBachtiarz\Auth\Job;

use Illuminate\Support\Facades\Hash;
use TheBachtiarz\Auth\Model\User;
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

class UserJob
{
    use ErrorLogTrait;

    /**
     * Model User data
     *
     * @var User
     */
    protected static User $user;

    /**
     * Old password.
     *
     * @var string
     */
    protected static string $oldPassword;

    /**
     * New password
     *
     * @var string
     */
    protected static string $newPassword;

    /**
     * Validate old password
     *
     * @var boolean
     */
    protected static bool $validateOldPassword = false;

    // ? Public Methods
    /**
     * Change password process
     *
     * @return array
     */
    public static function changePassword(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            /**
             * validate old password.
             * if validateOldPassword = true.
             */
            if (self::$validateOldPassword)
                throw_if(!Hash::check(self::$oldPassword, self::$user->password), 'Exception', "Incorrect old password");

            /**
             * update current user password
             */
            $_updatePassword = self::$user->update([
                'password' => Hash::make(self::$newPassword),
                'remember_token' => null
            ]);

            throw_if(!$_updatePassword, 'Exception', "Failed to change password");

            /**
             * delete all tokens
             */
            self::$user->tokens()->delete();

            $result['status'] = true;
            $result['message'] = "Successfully change password";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    // ? Private Methods

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
     * Set old password.
     *
     * @param string $oldPassword old password.
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
