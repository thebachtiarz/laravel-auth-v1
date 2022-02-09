<?php

namespace TheBachtiarz\Auth\Helper;

use Illuminate\Support\Facades\Log;
use TheBachtiarz\Auth\AuthInterface;
use TheBachtiarz\Toolkit\Config\Helper\ConfigHelper;

class MigrationHelper
{
    /**
     * file path
     *
     * @var string|null
     */
    private ?string $filePath = null;

    /**
     * message output
     *
     * @var string
     */
    private string $messageOutput = "";

    // ? Public Methods
    /**
     * remove migration's files process
     *
     * @return boolean
     */
    public function removeMigrationFiles(): bool
    {
        $result = false;
        $message = "";

        try {
            throw_if(!tbauthconfig('migration_remove_status'), 'Exception', "Migration removal process is canceled due to configuration setting.");

            /**
             * get all file from migration folder
             */
            $migrationFiles = glob(database_path("migrations/*.php"));
            throw_if(!count($migrationFiles), 'Exception', "There is no migration files");

            /**
             * search for files to delete
             */
            $removeFounds = [];
            foreach ($migrationFiles as $migrationKey => $migration)
                foreach (tbauthconfig('migration_files_remove') as $migrationFile => $fileName)
                    if (preg_match("/$fileName/", $migration))
                        $removeFounds[] = $migrationKey;

            throw_if(!count($removeFounds), 'Exception', "There is no any migration's file match with your config");

            /**
             * delete files process
             */
            foreach ($removeFounds as $key => $found) {
                $this->filePath = $migrationFiles[$found];
                $this->removeFile();
                $this->messageOutput();
            }

            /**
             * disable setting after removing migration's file process is done
             */
            ConfigHelper::setConfigName(AuthInterface::AUTH_CONFIG_NAME)->updateConfigFile('migration_remove_status', false);

            $message = "Successfully remove migration's files";
            $result = true;
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        } finally {
            $this->messageOutput = $message;
            $this->messageOutput();
            return $result;
        }
    }

    // ? Private Methods
    /**
     * remove file process
     *
     * @return boolean
     */
    private function removeFile(): bool
    {
        $result = false;
        $message = "";

        try {
            $_isWritable = is_writable($this->filePath);
            throw_if(!$_isWritable, 'Exception', "File {$this->filePath} is not writeable");

            $_removeFile = unlink($this->filePath);
            throw_if(!$_removeFile, 'Exception', "Failed to remove file {$this->filePath}");

            $message = "Remove migration's file: {$this->filePath}";
            $result = true;
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        } finally {
            $this->messageOutput = $message;
            return $result;
        }
    }

    /**
     * show message output remove migration file.
     *
     * @return void
     */
    private function messageOutput(): void
    {
        if (app()->runningInConsole())
            echo $this->messageOutput;
        else
            Log::channel('application')->debug($this->messageOutput);
    }

    // ? Setter Modules
}
