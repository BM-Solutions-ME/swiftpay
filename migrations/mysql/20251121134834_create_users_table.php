<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121134834";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            CREATE TABLE `users` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `type` enum('F','J') NOT NULL,
              `first_name` varchar(255) NOT NULL,
              `last_name` varchar(255) NOT NULL,
              `document` varchar(50) NOT NULL,
              `email` varchar(255) NOT NULL,
              `password` varchar(1000) NOT NULL,
              `level` tinyint(2) unsigned DEFAULT 0,
              `status` enum('registered','active','inactive','banned','canceled') DEFAULT 'registered',
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              UNIQUE KEY `document` (`document`),
              UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            DROP TABLE IF EXISTS `users`;
        ");
    }
};