<?php

namespace RecodeLang\DatabaseBackupLaravel;

use Illuminate\Support\ServiceProvider;
use RecodeLang\DatabaseBackupLaravel\Commands\BackupCommand;

class DatabaseBackupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole())
        {
            $this->commands([
                BackupCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
