<?php
namespace RecodeLang\DatabaseBackupLaravel;

use Illuminate\Support\Facades\Storage;

class DatabaseBackup
{    
    /**
     * storage
     *
     * @var mixed
     */
    private $storage, $filename, $path;
  
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->storage = Storage::disk('database_backup');
        $this->filename = now()
            ->format('Y-m-d_H-i-s') . '.sql';
        $this->path = storage_path($this->filename);
    }

    /**
     * createBackup
     * Creates a backup of the currently connected database
     *
     * @return void
     */
    public function createBackup()
    {
        // Exec mysqldump command with username, database name and password, host and path
        $command = "mysqldump --no-tablespaces -u " . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " -h " . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . $this->path;

        // Execute the command
        exec($command);
    }

    /**
     * deleteOldFiles
     * Delete files older than 30 days
     *
     * @return void
     */
    public function deleteOldFiles()
    {
        // Foreach all the files
        foreach ($this
            ->storage
            ->files(env('APP_NAME') . '/') as $file)
        {
            // Delete the file if it is older than 1 month
            if (now()->parse($this
                ->storage
                ->lastModified($file)) < now()->subMonth())
            {
                $this
                    ->storage
                    ->delete($file);
            }
        }
    }

    /**
     * getCreatedBackupAndRemove
     * Get the created backup and remove it from the disk
     *
     * @return string
     */
    private function getCreatedBackupAndRemove()
    {
        $contents = file_get_contents($this->path);
        unlink($this->path);
        return $contents;
    }

    /**
     * sendToDisk
     * Send to the S3 bucket 
     *
     * @return void
     */
    public function sendToDisk()
    {
        $this
            ->storage
            ->put(env('APP_NAME') . '/' . $this->filename, $this->getCreatedBackupAndRemove());
    }

}

