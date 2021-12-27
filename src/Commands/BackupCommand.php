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
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a backup of the currently connected database and sends it to the specified disk in the config file';

    private $backup;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->backup = new DatabaseBackup();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->backup->handle();
    }


}
