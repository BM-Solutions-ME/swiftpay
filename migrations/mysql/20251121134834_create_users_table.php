<?php

declare(strict_types=1);

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20251121134834";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        /*
         |-------------------------------------------------
         | TABLE
         |-------------------------------------------------
         */
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        /*
         |-------------------------------------------------
         | TRIGGER - AFTER INSERT
         |-------------------------------------------------
         */
        $driver->execute("
            CREATE TRIGGER trg_users_ai
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, new_data)
                VALUES (
                    'users',
                    NEW.id,
                    'created',
                    JSON_OBJECT(
                        'type', NEW.type,
                        'first_name', NEW.first_name,
                        'last_name', NEW.last_name,
                        'document', NEW.document,
                        'email', NEW.email,
                        'level', NEW.level,
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
            CREATE TRIGGER trg_users_au
            AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data, new_data)
                VALUES (
                    'users',
                    NEW.id,
                    'updated',
                    JSON_OBJECT(
                        'type', OLD.type,
                        'first_name', OLD.first_name,
                        'last_name', OLD.last_name,
                        'document', OLD.document,
                        'email', OLD.email,
                        'level', OLD.level,
                        'status', OLD.status
                    ),
                    JSON_OBJECT(
                        'type', NEW.type,
                        'first_name', NEW.first_name,
                        'last_name', NEW.last_name,
                        'document', NEW.document,
                        'email', NEW.email,
                        'level', NEW.level,
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
            CREATE TRIGGER trg_users_ad
            AFTER DELETE ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (entity, entity_id, action, old_data)
                VALUES (
                    'users',
                    OLD.id,
                    'deleted',
                    JSON_OBJECT(
                        'type', OLD.type,
                        'first_name', OLD.first_name,
                        'last_name', OLD.last_name,
                        'document', OLD.document,
                        'email', OLD.email,
                        'level', OLD.level,
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
         | DROP TRIGGERS (ordem segura)
         |-------------------------------------------------
         */
        $driver->execute("DROP TRIGGER IF EXISTS trg_users_ai;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_users_au;");
        $driver->execute("DROP TRIGGER IF EXISTS trg_users_ad;");

        /*
         |-------------------------------------------------
         | DROP TABLE
         |-------------------------------------------------
         */
        $driver->execute("DROP TABLE IF EXISTS `users`;");
    }
};
