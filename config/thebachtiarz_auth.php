<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Authentication Identity Method
    |--------------------------------------------------------------------------
    |
    | Here are method identity for user authentication.
    | Available ['email', 'username']
    |
    */
    'user_auth_identity_method' => 'username',

    /*
    |--------------------------------------------------------------------------
    | Migration files removal
    |--------------------------------------------------------------------------
    |
    | Here are list of migrations file will be remove.
    | Run when this module is published
    |
    */
    'migration_files_remove' => ['create_users_table'],
];
