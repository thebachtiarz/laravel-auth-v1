<?php

namespace TheBachtiarz\Auth\Traits\Model;

use TheBachtiarz\Auth\Model\User;

/**
 * Personal Access Token Map Trait
 */
trait PersonalAccessTokenScopeTrait
{
    /**
     * Current Model User data
     *
     * @var User
     */
    protected User $user;

    // ? Public Methods
    /**
     * Get user own tokens
     *
     * @param User $user
     * @return object|null
     */
    public function scopeGetOwnTokens($query, User $user, array $whereCondition = []): ?object
    {
        $this->user = $user;

        return $query->where($this->whereConditionResolver($whereCondition));
    }

    /**
     * Get token by user model and token name
     *
     * @param User $user
     * @param string $tokenName
     * @return object|null
     */
    public function scopeGetOwnTokenByName($query, User $user, string $tokenName): ?object
    {
        return $query->getOwnTokens($user, ['name' => $tokenName]);
    }

    // ? Private Methods
    /**
     * Where condition resolver
     *
     * @param array $whereConditionCustom default: []
     * @return array
     */
    private function whereConditionResolver(array $whereConditionCustom = []): array
    {
        $merge = array_merge(
            [
                'tokenable_type' => tbauthconfig('child_model_user_class') ?: User::class,
                'tokenable_id' => $this->user->id
            ],
            $whereConditionCustom
        );
        return $merge;
    }
}
