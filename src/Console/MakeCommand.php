<?php

namespace IgnitionWolf\API\Elastic\Console;

use Illuminate\Console\Command;

class MakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:elastic:make-migration {name} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

    /**
     * Configuration key of the storage directory for elastic migrations.
     */
    const CONFIG_KEY = 'elastic.migrations.storage_directory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');

        config([
            self::CONFIG_KEY => sprintf('Modules/%s/Database/ElasticMigrations/', $module)
        ]);

        $this->call("elastic:make:migration", [
            'name' => $name
        ]);

        return 0;
    }
}
