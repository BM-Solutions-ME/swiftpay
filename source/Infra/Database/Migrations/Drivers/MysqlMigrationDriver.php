<?php

namespace Source\Infra\Database\Migrations\Drivers;


use PDO;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

class MysqlMigrationDriver implements MigrationDriverInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function hasMigrationTable(): bool
    {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'migrations'");
        return ($stmt && $stmt->rowCount() > 0);
    }

    public function createMigrationTable(): void
    {
        $this->pdo->exec("CREATE TABLE migrations (id VARCHAR(255) PRIMARY KEY)");
    }

    public function isMigrationApplied(string $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM migrations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return (bool)$stmt->fetchColumn();
    }

    public function markMigrationAsApplied(string $id): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO migrations (id) VALUES (:id)");
        $stmt->execute(['id' => $id]);
    }

    public function unmarkMigration(string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function unmarkMigrationAsApplied(string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * @return array<string, mixed>
    */
    public function getAppliedMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT id FROM migrations ORDER BY id ASC");

        $listMigrations = [];
        if ($stmt) {
            $listMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        /** @var array<string, mixed> $listMigrations */
        return $listMigrations;
    }

    public function execute(string $command): void
    {
        $this->pdo->exec($command);
    }
}
