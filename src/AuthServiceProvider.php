<?php

namespace TheBachtiarz\Auth;

use Illuminate\Support\ServiceProvider;
use TheBachtiarz\Auth\Helper\MigrationHelper;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register module userlog
     *
     * @return void
     */
    public function register(): void
    {
        $applicationAuthService = new ApplicationAuthService;

        $applicationAuthService->registerConfig();

        if ($this->app->runningInConsole()) {
            $this->commands($applicationAuthService->registerCommands());
        }
    }

    /**
     * Boot module userlog
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/' . AuthInterface::AUTH_CONFIG_NAME . '.php' => config_path(AuthInterface::AUTH_CONFIG_NAME . '.php'),
            ], 'thebachtiarz-auth-config');

            // (new MigrationHelper)->removeMigrationFiles();

            // if (tbauthconfig('migration_remove_status')) {
            //     $this->publishes([
            //         __DIR__ . '/../database/migrations' => database_path('migrations'),
            //     ], 'thebachtiarz-auth-migrations');
            // }
        }
    }
}
