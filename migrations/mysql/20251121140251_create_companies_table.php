<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140251";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            CREATE TABLE `companies` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned NOT NULL,
              `public_name` varchar(255) NOT NULL,
              `legal_name` varchar(255) NOT NULL,
              `document` varchar(50) NOT NULL,
              `date_foundation` date DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              UNIQUE KEY `document` (`document`),
              KEY `fk_companies_user` (`user_id`),
              CONSTRAINT `fk_companies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            DROP TABLE IF EXISTS `companies`;
        ");
    }
};