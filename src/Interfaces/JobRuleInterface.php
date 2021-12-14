<?php

namespace TheBachtiarz\Auth\Interfaces;

interface JobRuleInterface
{
    public const JOB_RULE_TOKEN_GET_BY_DATA_USER = 'User';
    public const JOB_RULE_TOKEN_GET_BY_VAR_ID = 'id';
    public const JOB_RULE_TOKEN_GET_BY_VAR_USERNAME = 'username';
}
