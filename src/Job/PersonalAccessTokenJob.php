<?php

namespace TheBachtiarz\Auth\Job;

use TheBachtiarz\Auth\Model\{PersonalAccessToken, User};
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

class PersonalAccessTokenJob
{
    use ErrorLogTrait;

    /**
     * Model User data
     *
     * @var User
     */
    protected static User $user;

    /**
     * Token name value
     *
     * @var string
     */
    protected static string $tokenName;

    // ? Public Methods
    /**
     * Get all user token
     *
     * @param boolean $map
     * @return array
     */
    public static function getTokens(bool $map = false): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!self::$user, 'Exception', "There is no session");

            $_tokens = PersonalAccessToken::getOwnTokens(self::$user);

            throw_if(!$_tokens->count(), 'Exception', "User does not have token");

            $result['data'] = $map ? $_tokens->get()->map->simpleListMap() : $_tokens->get();
            $result['status'] = true;
            $result['message'] = "User token list";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * Find user token by token name
     *
     * @param boolean $map
     * @return array
     */
    public static function findToken(bool $map = false): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(!self::$user, 'Exception', "There is no session");

            $_token = PersonalAccessToken::getOwnTokenByName(self::$user, self::$tokenName)->first();

            throw_if(!$_token, 'Exception', "Token not found");

            $result['data'] = $map ? $_token->simpleListMap() : $_token;
            $result['status'] = true;
            $result['message'] = "Token detail";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * Delete user token by token name
     *
     * @return array
     */
    public static function deleteToken(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_token = self::findToken();

            throw_if(!$_token['status'], 'Exception', $_token['message']);

            $_token['data']->delete();

            $result['status'] = true;
            $result['message'] = "Successfully delete token";
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();

            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * Delete all user token
     *
     * @return array
     */
    public static function revokeTokens(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            $_tokens = self::getTokens();

            throw_if(!$_tokens['status'], 'Exception', $_tokens['message']);

            foreach ($_tokens['data'] as $key => &$token)
                $token->delete();

            $result['status'] = true;
            $result['message'] = "Successfully revoke all user token";
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
     * Set token name value
     *
     * @param string $tokenName token name value
     * @return self
     */
    public static function setTokenName(string $tokenName): self
    {
        self::$tokenName = $tokenName;

        return new self;
    }
}
