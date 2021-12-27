<?php

namespace RecodeLang\DatabaseBackupLaravel;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup
{
    private $storage, $filename, $path;

    // Constructor
    public function __construct()
    {
        $this->storage = Storage::disk('database_backup');
        $this->filename = now()->format('Y-m-d_H-i-s') . '.sql';
        $this->path = storage_path($this->filename);
    }

    // Private methods
    // Creates a backup of the database
    private function createBackup()
    {
        // Exec mysqldump command with username, database name and password, host and path
        $command = "mysqldump --no-tablespaces -u " . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " -h " . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . $this->path;

        // Execute the command
        exec($command);
    }

    // Deletes old backups
    private function deleteOldFiles()
    {
        // Foreach all the files
        foreach ($this->storage->files(env('APP_NAME') . '/') as $file) {
            // Delete the file if it is older than 1 month
            if (now()->parse($this->storage->lastModified($file)) < now()->subMonth()) {
                $this->storage->delete($file);
            }
        }
    }

    // Gets the locally stored backup file and returns it
    private function getCreatedBackupAndRemove ()
    {
        $contents = file_get_contents($this->path);
        unlink($this->path);
        return $contents;
    }

    // Send to the S3 bucket
    private function sendToDisk()
    {
        $this->storage->put(env('APP_NAME') . '/' . $this->filename, $this->getCreatedBackupAndRemove());
    }

    public function handle()
    {
        Log::info('Attempting backup...');

        try {
            // Delete old files
            $this->deleteOldFiles();

            // Create a backup of the database
            $this->createBackup();

            // Send to the disk
            $this->sendToDisk();
            Log::info('Backup successful!');
            return "Backup successful!";
        } catch (\Exception $e) {
            Log::error('Backup failed!');
            Log::error($e->getMessage());
            return "Backup failed!";
        }
    }

}
