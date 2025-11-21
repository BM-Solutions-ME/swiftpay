<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140506";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            CREATE TABLE `transfer` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `wallet_sender` bigint(20) unsigned NOT NULL,
              `wallet_receiver` bigint(20) unsigned NOT NULL,
              `amount` int(10) unsigned NOT NULL CHECK (`amount` > 0),
              `status` enum('pending','processing','completed','failed','canceled','reversed') DEFAULT 'pending',
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `fk_transfer_wallet_sender` (`wallet_sender`),
              KEY `fk_transfer_wallet_receiver` (`wallet_receiver`),
              CONSTRAINT `fk_transfer_wallet_receiver` FOREIGN KEY (`wallet_receiver`) REFERENCES `wallet` (`id`) ON UPDATE CASCADE,
              CONSTRAINT `fk_transfer_wallet_sender` FOREIGN KEY (`wallet_sender`) REFERENCES `wallet` (`id`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        $driver->execute("
            DROP TABLE IF EXISTS `transfer`;
        ");
    }
};