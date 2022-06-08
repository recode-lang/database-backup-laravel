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
    public function __construct($disk)
    {
        // Get the storage disk from the config file
        $this->storage = Storage::disk($disk);
        // Create the filename
        $this->filename = now()->format('Y-m-d_H-i-s') . '.sql';
        // Create the path based on the filename
        $this->path = storage_path($this->filename);
    }

    /**
     * createBackup
     * Creates a backup of the currently connected database
     *
     * @return void
     */
    private function createBackup()
    {
        // Exec mysqldump command with username, database name and password, host and path
        $command = "mysqldump --no-tablespaces -u " . config('database.connections.mysql.username') . " -p" . config('database.connections.mysql.password') . " -h " . config('database.connections.mysql.host') . " " . config('database.connections.mysql.database') . " > " . $this->path . " 2>/dev/null";

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
        // Foreach all the files in the storage disk
        foreach ($this->storage->files(config('app.name') . '/') as $file)
        {
            // Delete the file if it is older than 1 month
            if (now()->parse($this->storage->lastModified($file)) < now()->subMonth())
            {
                // Delete the file
                $this->storage->delete($file);
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
        // Get the backup file
        $contents = file_get_contents($this->path);
        // Remove the backup file
        unlink($this->path);
        // Return the backup file's contents
        return $contents;
    }

    /**
     * sendToDisk
     * Make the backup, check if the content isn't empty, then send it to the disk
     *
     * @return boolean
     */
    public function makeBackupAndSendToDisk()
    {
        // Create the backup
        $this->createBackup();
        // Get the backup file's contents
        $content = $this->getCreatedBackupAndRemove();
        // Check if the content isn't empty
        if(!empty($content)){
            // Send the backup file to the disk and return true
            $this->storage->put(config('app.name') . '/' . $this->filename, $content);
            return true;
        }
        // If the content is empty, return false
        return false;
    }

}

