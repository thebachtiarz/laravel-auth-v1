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
     * personal access token simple list map
     *
     * @return array
     */
    public function simpleListMap(): array
    {
        return [
            'identifier' => $this->name,
            'created' => $this->created_at,
            'last_used' => $this->last_used_at ? self::humanDateTime($this->last_used_at) : '-'
        ];
    }
}
