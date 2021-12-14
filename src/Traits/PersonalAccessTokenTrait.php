<?php

namespace TheBachtiarz\Auth\Traits;

/**
 * PersonalAccessToken Trait
 */
trait PersonalAccessTokenTrait
{
    public function simpleListMap(): ?array
    {
        return [
            'identifier' => $this->name,
            'created' => $this->created_at,
            'last_used' => $this->last_used_at ? $this->humanDateTime($this->last_used_at) : '-'
        ];
    }
}
