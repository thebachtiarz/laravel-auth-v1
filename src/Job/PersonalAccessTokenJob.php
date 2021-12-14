<?php

namespace TheBachtiarz\Auth\Job;

use TheBachtiarz\Auth\Interfaces\JobRuleInterface;
use TheBachtiarz\Auth\Model\PersonalAccessToken;
use TheBachtiarz\Auth\Model\User;

class PersonalAccessTokenJob
{
    //
    protected static User $user;

    private static int $id;
    private static string $username;

    private static string $searchTokenBy;

    // ? Public Methods
    // create method

    /**
     * find access token by id
     *
     * @return PersonalAccessToken|null
     */
    public static function find(): ?PersonalAccessToken
    {
        return self::searchToken();
    }

    /**
     * search token by User|username
     *
     * @return object|null
     */
    public static function get(): ?object
    {
        return self::searchTokens();
    }

    /**
     * delete token by id
     *
     * @return boolean
     */
    public static function delete(): bool
    {
        try {
            return self::searchToken()->delete();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * delete token by User|username
     *
     * @return boolean
     */
    public static function revoke(): bool
    {
        try {
            foreach (self::searchTokens() as $key => $token)
                $token->delete();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    // ? Private Methods
    /**
     * find access token by id process
     *
     * @return PersonalAccessToken|null
     */
    private static function searchToken(): ?PersonalAccessToken
    {
        $class = PersonalAccessToken::class;

        try {
            $result = $class::find(self::$id);
            throw_if(!$result, 'Exception', "Token not found!");

            return $result;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * search token by User|username process
     *
     * @return object|null
     */
    private static function searchTokens(): ?object
    {
        $class = PersonalAccessToken::class;

        try {
            throw_if(!in_array(self::$searchTokenBy, [
                JobRuleInterface::JOB_RULE_TOKEN_GET_BY_DATA_USER,
                JobRuleInterface::JOB_RULE_TOKEN_GET_BY_VAR_USERNAME
            ]), 'Exception', "Search by is not allowed!");

            if (self::$searchTokenBy === JobRuleInterface::JOB_RULE_TOKEN_GET_BY_DATA_USER)
                $result = $class::where('tokenable_id', self::$user->id);

            if (self::$searchTokenBy === JobRuleInterface::JOB_RULE_TOKEN_GET_BY_VAR_USERNAME)
                $result = $class::where('name', 'like', '%' . self::$username . '%');

            throw_if(!$result->count(), 'Exception', "Tokens not found");

            $result = $result->get();

            return $result;
        } catch (\Throwable $th) {
            return null;
        }
    }

    // ? Setter Modules
    /**
     * set [User] data
     *
     * @param User $user
     * @return self
     */
    public static function setUser(User $user): self
    {
        self::$user = $user;
        self::$searchTokenBy = JobRuleInterface::JOB_RULE_TOKEN_GET_BY_DATA_USER;

        return new self;
    }

    /**
     * set id
     *
     * @param integer $id
     * @return self
     */
    public static function setId(int $id): self
    {
        self::$id = $id;
        self::$searchTokenBy = JobRuleInterface::JOB_RULE_TOKEN_GET_BY_VAR_ID;

        return new self;
    }

    /**
     * set username
     *
     * @param string $username
     * @return self
     */
    public static function setUsername(string $username): self
    {
        self::$username = $username;
        self::$searchTokenBy = JobRuleInterface::JOB_RULE_TOKEN_GET_BY_VAR_USERNAME;

        return new self;
    }
}
