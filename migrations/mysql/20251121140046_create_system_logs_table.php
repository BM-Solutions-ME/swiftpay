<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140046";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            CREATE TABLE `system_logs` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `entity` varchar(50) NOT NULL,
              `entity_id` bigint(20) unsigned NOT NULL,
              `action` enum('created','updated','deleted','status_changed') NOT NULL,
              `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
              `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
              `performed_by` bigint(20) unsigned DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `idx_entity` (`entity`,`entity_id`),
              KEY `performed_by` (`performed_by`),
              CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            DROP TABLE IF EXISTS `system_logs`;
        ");
    }
};