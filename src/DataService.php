<?php

namespace TheBachtiarz\Auth;

use TheBachtiarz\Auth\Interfaces\ConfigInterface;

class DataService
{
    /**
     * list of config who need to registered into current project.
     * perform by auth app module.
     *
     * @return array
     */
    public static function registerConfig(): array
    {
        $registerConfig = [];

        // ! auth
        $registerConfig[] = [];

        return $registerConfig;
    }
}
