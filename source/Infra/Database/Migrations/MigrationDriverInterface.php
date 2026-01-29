<?php

namespace Source\Infra\Database\Migrations;

interface MigrationDriverInterface
{
    /**
     * @return bool
     */
    public function hasMigrationTable(): bool;

    /**
     * @return void
     */
    public function createMigrationTable(): void;

    /**
     * @param string $id
     * @return bool
     */
    public function isMigrationApplied(string $id): bool;

    /**
     * @param string $id
     * @return void
     */
    public function markMigrationAsApplied(string $id): void;

    /**
     * @param string $id
     * @return void
     */
    public function unmarkMigrationAsApplied(string $id): void;
    /**
     * @return array<string, mixed>
    */
    public function getAppliedMigrations(): array;

    /**
     * @param string $command
     * @return void
     */
    public function execute(string $command): void;
}