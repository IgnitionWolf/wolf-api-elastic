<?php

namespace IgnitionWolf\API\Elastic\Filesystem;

use ElasticMigrations\Filesystem\MigrationFile;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

final class MigrationStorage extends \ElasticMigrations\Filesystem\MigrationStorage
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $directory;

    /**
     * glob path to access the migrations in all modules.
     */
    const MODULES_MIGRATIONS_PATH = 'Modules/**/Database/ElasticMigrations/*_*.php';

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);

        $this->filesystem = $filesystem;
        $this->directory = new class() {
            public function __toString()
            {
                return rtrim(config('elastic.migrations.storage_directory'), '/');
            }
        };
    }

    public function create(string $fileName, string $content): MigrationFile
    {
        if (!$this->filesystem->isDirectory($this->directory)) {
            $this->filesystem->makeDirectory($this->directory, 0755, true);
        }

        $filePath = $this->resolvePath($fileName);
        $this->filesystem->put($filePath, $content);

        return new MigrationFile($filePath);
    }

    public function findAll(): Collection
    {
        $files = $this->filesystem->glob($this->directory . '/*_*.php');
        $files = array_merge($files, $this->filesystem->glob(static::MODULES_MIGRATIONS_PATH));

        return collect($files)->sort()->map(static function (string $filePath) {
            return new MigrationFile($filePath);
        });
    }

    public function findByName(string $fileName): ?MigrationFile
    {
        $filePath = $this->resolvePath($fileName);
        if ($this->filesystem->exists($filePath)) {
            return new MigrationFile($filePath);
        }

        $glob = $this->filesystem->glob(
            $this->resolvePath($fileName, str_replace(
                '/*_*.php',
                '',
                static::MODULES_MIGRATIONS_PATH
            ))
        );

        return $glob ? new MigrationFile($glob[0]) : null;
    }

    private function resolvePath(string $fileName, $directory = null): string
    {
        return sprintf('%s/%s.php', $directory ?? $this->directory, str_replace('.php', '', trim($fileName)));
    }

    public function isReady(): bool
    {
        return $this->filesystem->isDirectory($this->directory) || (
            count($this->filesystem->glob(static::MODULES_MIGRATIONS_PATH)) > 0
        );
    }
}
