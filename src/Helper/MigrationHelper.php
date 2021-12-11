<?php

namespace TheBachtiarz\Auth\Helper;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Support\Facades\Log;

class MigrationHelper
{
    use InteractsWithIO;

    private ?string $filePath = null;
    private string $messageOutput = "";
    private bool $statusOutput = false;

    // ? Public Methods
    public function removeMigrationFiles(): bool
    {
        $result = false;
        $message = "";

        try {
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
     * only if running in console.
     *
     * @return void
     */
    private function messageOutput(): void
    {
        if (app()->runningInConsole()) {
            if ($this->statusOutput)
                $this->info($this->messageOutput);
            else
                $this->warn($this->messageOutput);
        } else {
            Log::channel('application')->debug($this->messageOutput);
        }
    }

    // ? Setter Modules
}
