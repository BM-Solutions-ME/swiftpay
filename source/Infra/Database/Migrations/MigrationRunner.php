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

        $migrationFiles = glob(__DIR__ . "/../../../../migrations/" . $this->driverName . "/*.php") ?: [];

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
        // Garante que a tabela existe
        if (!$this->driver->hasMigrationTable()) {
            return; // Nada para remover
        }

        // Recupera todas as migrations aplicadas
        $appliedMigrations = $this->driver->getAppliedMigrations();

        if (empty($appliedMigrations)) {
            return; // Nada para fazer
        }

        // Carrega TODOS os arquivos de migrations
        $migrationFiles = glob(__DIR__ . "/../../../../migrations/" . $this->driverName . "/*.php") ?: [];

        // Indexa os arquivos por ID da migration
        $migrationsById = [];

        foreach ($migrationFiles as $file) {
            /** @var MigrationInterface $migration */
            $migration = require $file;
            $migrationsById[$migration->id()] = $migration;
        }

        // Ordena em ordem reversa (rollback correto)
        rsort($appliedMigrations);

        foreach ($appliedMigrations as $migrationId) {
            // Só processa se houver o arquivo correspondente
            if (!isset($migrationsById[$migrationId])) {
                continue; // Arquivo removido? Ignorar.
            }

            $migration = $migrationsById[$migrationId];

            // Executa o rollback
            $migration->down($this->driver);

            // Marca como removida
            $this->driver->unmarkMigrationAsApplied($migrationId);
        }
    }
}
