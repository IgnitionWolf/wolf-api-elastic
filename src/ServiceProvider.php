<?php

namespace IgnitionWolf\API\Elastic;

use ElasticMigrations\Console\MakeCommand;
use ElasticMigrations\Filesystem\MigrationStorage;
use ElasticMigrations\Migrator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

final class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    private $commands = [
        \IgnitionWolf\API\Elastic\Console\MakeCommand::class
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        if (App::runningInConsole()) {
            $this->app->when([Migrator::class, MakeCommand::class])
                    ->needs(MigrationStorage::class)
                    ->give(\IgnitionWolf\API\Elastic\Filesystem\MigrationStorage::class);
        }
    }

    /**
     * @return void
     */
    public function boot()
    {
        if (App::runningInConsole()) {
            $this->commands($this->commands);
        }
    }
}