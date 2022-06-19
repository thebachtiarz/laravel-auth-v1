<?php

namespace TheBachtiarz\Auth\Traits\Model;

use TheBachtiarz\Toolkit\Helper\App\Carbon\CarbonHelper;

/**
 * Personal Access Token Map Trait
 */
trait PersonalAccessTokenMapTrait
{
    use CarbonHelper;

    /**
     * Personal access token simple list map
     *
     * @return array
     */
    public function simpleListMap(): array
    {
        return [
            'token_name' => $this->name,
            'created' => self::humanDateTime($this->created_at),
            'last_used' => $this->last_used_at ? self::humanDateTime($this->last_used_at) : '-'
        ];
    }
}
