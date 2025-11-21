<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140409";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            CREATE TABLE `wallet` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned NOT NULL,
              `balance` int(11) NOT NULL DEFAULT 0,
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `fk_wallet_user` (`user_id`),
              CONSTRAINT `fk_wallet_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            DROP TABLE IF EXISTS `wallet`;
        ");
    }
};