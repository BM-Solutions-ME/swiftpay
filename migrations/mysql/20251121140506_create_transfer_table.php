<?php

declare(strict_types=1);

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140506";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        /*
         |-------------------------------------------------
         | TABLE
         |-------------------------------------------------
         */
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
              CONSTRAINT `fk_transfer_wallet_receiver`
                FOREIGN KEY (`wallet_receiver`) REFERENCES `wallet` (`id`) ON UPDATE CASCADE,
              CONSTRAINT `fk_transfer_wallet_sender`
                FOREIGN KEY (`wallet_sender`) REFERENCES `wallet` (`id`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        /*
         |-------------------------------------------------
         | TRIGGER - AFTER INSERT
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TRIGGER trg_transfer_ai
            AFTER INSERT ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, new_data)
                VALUES (
                    'transfer',
                    NEW.id,
                    'created',
                    JSON_OBJECT(
                        'wallet_sender', NEW.wallet_sender,
                        'wallet_receiver', NEW.wallet_receiver,
                        'amount', NEW.amount,
                        'status', NEW.status
                    )
                );
            END;
        ");

        /*
         |-------------------------------------------------
         | TRIGGER - AFTER UPDATE
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TRIGGER trg_transfer_au
            AFTER UPDATE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data, new_data)
                VALUES (
                    'transfer',
                    NEW.id,
                    IF(OLD.status <> NEW.status, 'status_changed', 'updated'),
                    JSON_OBJECT(
                        'wallet_sender', OLD.wallet_sender,
                        'wallet_receiver', OLD.wallet_receiver,
                        'amount', OLD.amount,
                        'status', OLD.status
                    ),
                    JSON_OBJECT(
                        'wallet_sender', NEW.wallet_sender,
                        'wallet_receiver', NEW.wallet_receiver,
                        'amount', NEW.amount,
                        'status', NEW.status
                    )
                );
            END;
        ");

        /*
         |-------------------------------------------------
         | TRIGGER - AFTER DELETE
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TRIGGER trg_transfer_ad
            AFTER DELETE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data)
                VALUES (
                    'transfer',
                    OLD.id,
                    'deleted',
                    JSON_OBJECT(
                        'wallet_sender', OLD.wallet_sender,
                        'wallet_receiver', OLD.wallet_receiver,
                        'amount', OLD.amount,
                        'status', OLD.status
                    )
                );
            END;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        /*
         |-------------------------------------------------
         | DROP TRIGGERS
         |-------------------------------------------------
         */
        $driver->execute("DROP TRIGGER IF EXISTS trg_transfer_ai;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_transfer_au;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_transfer_ad;");

        /*
         |-------------------------------------------------
         | DROP TABLE
         |-------------------------------------------------
         */
        $driver->execute("DROP TABLE IF EXISTS `transfer`;");
    }
};
