<?php

namespace TheBachtiarz\Auth\Job;

use Illuminate\Support\Facades\{Auth, Hash};
use TheBachtiarz\Auth\Model\User;
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

class UserJob
{
    use ErrorLogTrait;

    /**
     * Model User ddata
     *
     * @var User
     */
    protected static User $user;

    /**
     * old password.
     *
     * @var string
     */
    protected static string $oldPassword;

    /**
     * new password
     *
     * @var string
     */
    protected static string $newPassword;

    /**
     * validate with old password
     *
     * @var boolean
     */
    protected static bool $validateWithOldPassword = false;

    // ? Public Methods

    /**
     * change password
     *
     * @return array
     */
    public static function changePassword(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!Auth::check(), 'Exception', "There is no session");

            /**
             * validate old password.
             * if validateWithOldPassword = true.
             */
            if (self::$validateWithOldPassword)
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

            /**
             * logout current session
             */
            Auth::logout();

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
     * Set model User ddata
     *
     * @param User $user Model User ddata
     * @return self
     */
    public static function setUser(User $user): self
    {
        self::$user = $user;

        return new self;
    }

    /**
     * Set old password.
     * require: UserJob::setValidateWithOldPassword(true)
     *
     * @param string $oldPassword old password.
     * @return self
     */
    public static function setOldPassword(string $oldPassword): self
    {
        self::$oldPassword = $oldPassword;

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
     * Set validate with old password
     *
     * @param boolean $validateWithOldPassword validate with old password
     * @return self
     */
    public static function setValidateWithOldPassword(bool $validateWithOldPassword = false): self
    {
        self::$validateWithOldPassword = $validateWithOldPassword;

        return new self;
    }
}
