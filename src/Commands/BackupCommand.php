<?php

namespace RecodeLang\DatabaseBackupLaravel\Commands;

use Illuminate\Console\Command;
use RecodeLang\DatabaseBackupLaravel\DatabaseBackup;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {disk?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a backup of the currently connected database and sends it to the specified disk';

    private $backup;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->backup = new DatabaseBackup($this->argument('disk') ?? 's3');
        $this->info('Deleting old backups...');
        $this->backup->deleteOldFiles();
        $this->info('Done, creating new backup...');
        if($this->backup->makeBackupAndSendToDisk()) {
            $this->info('Backup created and sent to disk.');
        } else {
            $this->error('Backup failed.');
        }
    }


}
