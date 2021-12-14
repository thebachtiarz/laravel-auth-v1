<?php

namespace TheBachtiarz\Auth\Model;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use TheBachtiarz\Auth\Traits\PersonalAccessTokenTrait;
use TheBachtiarz\Toolkit\Helper\App\Carbon\CarbonHelper;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use PersonalAccessTokenTrait, CarbonHelper;
}
