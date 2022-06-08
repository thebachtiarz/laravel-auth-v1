<?php

namespace TheBachtiarz\Auth\Interfaces\Validator;

use TheBachtiarz\Toolkit\Helper\Interfaces\Validator\GlobalValidatorInterface;

interface AuthValidatorInterface
{
    // ? Rules

    /**
     * Auth login email rules
     */
    public const AUTH_LOGIN_EMAIL_RULES = [
        'email' => ["required", "email"]
    ] + self::AUTH_PASSWORD_RULES;

    /**
     * Auth login username rules
     */
    public const AUTH_LOGIN_USERNAME_RULES = [
        'username' => ["required", "min:8", "alpha_num"]
    ] + self::AUTH_PASSWORD_RULES;

    /**
     * Auth password rules
     */
    public const AUTH_PASSWORD_RULES = [
        'password' => ["required", "string", GlobalValidatorInterface::RULES_REGEX_PASSWORD_SECURE]
    ];

    /**
     * Auth password old rules
     */
    public const AUTH_PASSWORD_OLD_RULES = [
        'password_old' => ["required", "string", GlobalValidatorInterface::RULES_REGEX_PASSWORD_SECURE]
    ];

    /**
     * Auth logout rules
     */
    public const AUTH_LOGOUT_RULES = [
        'revoke' => ["nullable", "boolean"]
    ];

    /**
     * Auth token name rules
     */
    public const AUTH_TOKEN_NAME_RULES = [
        'token_name' => ["required", "regex:/^[a-zA-Z0-9|]+$/"]
    ];

    // ? Messages

    /**
     * Auth login email messages
     */
    public const AUTH_LOGIN_EMAIL_MESSAGES = [
        'email.email' => 'Invalid email address'
    ] + self::AUTH_PASSWORD_MESSAGES;

    /**
     * Auth login username messages
     */
    public const AUTH_LOGIN_USERNAME_MESSAGES = [
        'username.min' => 'Username must be more than :min chars',
        'username.alpha_num' => 'Username must be an alpha numeric chars'
    ] + self::AUTH_PASSWORD_MESSAGES;

    /**
     * Auth password messages
     */
    public const AUTH_PASSWORD_MESSAGES = [
        'password.regex' => 'Password combination cannot be accepted'
    ];

    /**
     * Auth password old messages
     */
    public const AUTH_PASSWORD_OLD_MESSAGES = [
        'password_old.regex' => 'Incorrect format old password'
    ];

    /**
     * Auth logout messages
     */
    public const AUTH_LOGOUT_MESSAGES = [
        'revoke.boolean' => 'Logout to all devices failed'
    ];

    /**
     * Auth token name messages
     */
    public const AUTH_TOKEN_NAME_MESSAGES = [
        'token_name.regex' => 'Incorrect token name format'
    ];
}
