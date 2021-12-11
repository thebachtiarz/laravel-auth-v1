<?php

use TheBachtiarz\Auth\AuthInterface;

/**
 * thebachtiarz auth config
 *
 * @param string|null $keyName config key name | null will return all
 * @return mixed|null
 */
function tbuserlogconfig(?string $keyName = null)
{
    $configName = AuthInterface::AUTH_CONFIG_NAME;

    return iconv_strlen($keyName)
        ? config("{$configName}.{$keyName}")
        : config("{$configName}");
}
