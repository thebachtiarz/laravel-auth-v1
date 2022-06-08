<?php

namespace TheBachtiarz\Auth\Traits\Model;

use Illuminate\Support\Facades\DB;

/**
 * User Scope Trait
 */
trait UserScopeTrait
{
    //

    // ? Public Methods
    /**
     * get user by identifier
     *
     * @param string $identifier
     * @return object|null
     */
    public function scopeGetByIdentifier($query, string $identifier): ?object
    {
        $_identifier = tbauthconfig('user_auth_identity_method');

        return $query->where(DB::raw("BINARY `$_identifier`"), $identifier);
    }

    // ? Private Methods

    // ? Setter Modules
}
