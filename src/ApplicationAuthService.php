<?php

namespace TheBachtiarz\Auth;

class ApplicationAuthService
{
    /**
     * list of commands from auth modules
     */
    public const COMMANDS = [];

    // ? Public Methods
    /**
     * register config
     *
     * @return boolean
     */
    public function registerConfig(): bool
    {
        try {
            $this->setConfigs();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * register commands
     *
     * @return array
     */
    public function registerCommands(): array
    {
        try {
            return self::COMMANDS;
        } catch (\Throwable $th) {
            return [];
        }
    }

    // ? Private Methods
    /**
     * set configs
     *
     * @return void
     */
    private function setConfigs(): void
    {
        foreach (DataService::registerConfig() as $key => $register)
            config($register);
    }
}
