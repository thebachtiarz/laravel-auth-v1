<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Authentication Identity Method
    |--------------------------------------------------------------------------
    |
    | Here are method identity for user authentication.
    | example: email
    |
    */
    'user_auth_identity_method' => "email",

    /*
    |--------------------------------------------------------------------------
    | Class Model User Child
    |--------------------------------------------------------------------------
    |
    | Define the child class which extending the "TheBachtiarz\Auth\Model\User::class".
    | example: "App\Models\User" or \App\Models\User::class.
    | Leave blank if not using child class.
    |
    */
    'child_model_user_class' => "App\Models\User",

    /*
    |--------------------------------------------------------------------------
    | Migration status removal
    |--------------------------------------------------------------------------
    |
    | Here are status condition for run migration removal.
    | Disable if don't want to remove migration's files.
    |
    */
    'migration_remove_status' => false,

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
