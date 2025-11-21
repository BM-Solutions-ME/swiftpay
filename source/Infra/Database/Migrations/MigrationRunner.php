<?php

namespace Source\Infra\Database\Migrations;


class MigrationRunner
{
    public function __construct(
        private readonly MigrationDriverInterface $driver,
        private readonly string                   $driverName
    )
    {
    }

    public function runUp(): void
    {
        if (!$this->driver->hasMigrationTable()) {
            $this->driver->createMigrationTable();
        }

        $migrationFiles = glob(__DIR__ . "/../../../../migrations/" . $this->driverName . "/*.php");

        foreach ($migrationFiles as $file) {
            /** @var MigrationInterface $migration */
            $migration = require($file);

            if (!$this->driver->isMigrationApplied($migration->id())) {
                $migration->up($this->driver);
                $this->driver->markMigrationAsApplied($migration->id());
            }
        }
    }

    public function runDown(): void
    {
        // limpa tudo (ex: CI)
    }
}
