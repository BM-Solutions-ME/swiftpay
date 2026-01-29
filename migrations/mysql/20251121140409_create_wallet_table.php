<?php

declare(strict_types=1);

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121140409";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        /*
         |-------------------------------------------------
         | TABLE
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TABLE `wallet` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned NOT NULL,
              `company_id` bigint(20) unsigned NULL,
              `title` varchar(255) NOT NULL,
              `balance` int(11) NOT NULL DEFAULT 0,
              `created_at` timestamp NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `fk_wallet_user` (`user_id`),
              KEY `fk_wallet_company` (`company_id`),
              CONSTRAINT `fk_wallet_user`
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
              CONSTRAINT `fk_wallet_company`
                FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
                ON UPDATE CASCADE
                ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        /*
         |-------------------------------------------------
         | TRIGGER - AFTER INSERT
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TRIGGER trg_wallet_ai
            AFTER INSERT ON wallet
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, new_data)
                VALUES (
                    'wallet',
                    NEW.id,
                    'created',
                    JSON_OBJECT(
                        'user_id', NEW.user_id,
                        'company_id', NEW.company_id,
                        'title', NEW.title,
                        'balance', NEW.balance
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
            CREATE TRIGGER trg_wallet_au
            AFTER UPDATE ON wallet
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data, new_data)
                VALUES (
                    'wallet',
                    NEW.id,
                    'updated',
                    JSON_OBJECT(
                        'user_id', OLD.user_id,
                        'company_id', OLD.company_id,
                        'title', OLD.title,
                        'balance', OLD.balance
                    ),
                    JSON_OBJECT(
                        'user_id', NEW.user_id,
                        'company_id', NEW.company_id,
                        'title', NEW.title,
                        'balance', NEW.balance
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
            CREATE TRIGGER trg_wallet_ad
            AFTER DELETE ON wallet
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data)
                VALUES (
                    'wallet',
                    OLD.id,
                    'deleted',
                    JSON_OBJECT(
                        'user_id', OLD.user_id,
                        'company_id', OLD.company_id,
                        'title', OLD.title,
                        'balance', OLD.balance
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
        $driver->execute("DROP TRIGGER IF EXISTS trg_wallet_ai;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_wallet_au;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_wallet_ad;");

        /*
         |-------------------------------------------------
         | DROP TABLE
         |-------------------------------------------------
         */
        $driver->execute("DROP TABLE IF EXISTS `wallet`;");
    }
};
