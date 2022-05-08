<?php

namespace TheBachtiarz\Auth\Interfaces\Validator;

use TheBachtiarz\Toolkit\Helper\Interfaces\Validator\GlobalValidatorInterface;

interface AuthValidatorInterface
{
    // ? Rules

    /**
     * auth login email rules
     */
    public const AUTH_LOGIN_EMAIL_RULES = [
        'email' => ["required", "email"],
        'password' => ["required", "string", GlobalValidatorInterface::RULES_REGEX_PASSWORD_SECURE]
    ];

    /**
     * auth login username rules
     */
    public const AUTH_LOGIN_USERNAME_RULES = [
        'username' => ["required", "min:8", "alpha_num"],
        'password' => ["required", "string", GlobalValidatorInterface::RULES_REGEX_PASSWORD_SECURE]
    ];

    /**
     * auth logout rules
     */
    public const AUTH_LOGOUT_RULES = [
        'revoke' => ["nullable", "boolean"]
    ];

    // ? Messages

    /**
     * auth login email messages
     */
    public const AUTH_LOGIN_EMAIL_MESSAGES = [
        'email.email' => 'Invalid email address',
        'password.regex' => 'Password combination cannot be accepted'
    ];

    /**
     * auth login username messages
     */
    public const AUTH_LOGIN_USERNAME_MESSAGES = [
        'username.min' => 'Username must be more than :min chars',
        'username.alpha_num' => 'Username must be an alpha numeric chars',
        'password.regex' => 'Password combination cannot be accepted'
    ];

    /**
     * auth logout messages
     */
    public const AUTH_LOGOUT_MESSAGES = [
        'revoke.boolean' => 'Logout to all devices failed'
    ];
}
