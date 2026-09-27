<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20260916022140";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        /*
         * -------------------------------------------------
         * ADD IDEMPOTENCY KEY
         * -------------------------------------------------
         */

        $driver->execute("
            ALTER TABLE `transfer`
            ADD COLUMN `idempotency_key` VARCHAR(100) NULL
            AFTER `id`;
        ");

        /*
         * -------------------------------------------------
         * FILL EXISTING RECORDS
         * -------------------------------------------------
         *
         * As transferências antigas não possuem uma
         * idempotency_key. Criamos uma chave exclusiva
         * baseada no próprio ID.
         */

        $driver->execute("
            UPDATE `transfer`
            SET `idempotency_key` = CONCAT('legacy-', `id`)
            WHERE `idempotency_key` IS NULL;
        ");

        /*
         * -------------------------------------------------
         * MAKE IDEMPOTENCY KEY REQUIRED
         * -------------------------------------------------
         */

        $driver->execute("
            ALTER TABLE `transfer`
            MODIFY COLUMN `idempotency_key` VARCHAR(100) NOT NULL;
        ");

        /*
         * -------------------------------------------------
         * UNIQUE INDEX
         * -------------------------------------------------
         */

        $driver->execute("
            ALTER TABLE `transfer`
            ADD UNIQUE KEY `uq_transfer_idempotency_key`
            (`idempotency_key`);
        ");

        /*
         * -------------------------------------------------
         * DROP OLD TRIGGERS
         * -------------------------------------------------
         */

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_ai`;
        ");

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_au`;
        ");

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_ad`;
        ");

        /*
         * -------------------------------------------------
         * TRIGGER - AFTER INSERT
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_ai`
            AFTER INSERT ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    new_data
                )
                VALUES (
                    'transfer',
                    NEW.id,
                    'created',
                    JSON_OBJECT(
                        'wallet_sender', NEW.wallet_sender,
                        'wallet_receiver', NEW.wallet_receiver,
                        'amount', NEW.amount,
                        'status', NEW.status,
                        'idempotency_key', NEW.idempotency_key
                    )
                );
            END;
        ");

        /*
         * -------------------------------------------------
         * TRIGGER - AFTER UPDATE
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_au`
            AFTER UPDATE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    old_data,
                    new_data
                )
                VALUES (
                    'transfer',
                    NEW.id,
                    IF(
                        OLD.status <> NEW.status,
                        'status_changed',
                        'updated'
                    ),
                    JSON_OBJECT(
                        'wallet_sender', OLD.wallet_sender,
                        'wallet_receiver', OLD.wallet_receiver,
                        'amount', OLD.amount,
                        'status', OLD.status,
                        'idempotency_key', OLD.idempotency_key
                    ),
                    JSON_OBJECT(
                        'wallet_sender', NEW.wallet_sender,
                        'wallet_receiver', NEW.wallet_receiver,
                        'amount', NEW.amount,
                        'status', NEW.status,
                        'idempotency_key', NEW.idempotency_key
                    )
                );
            END;
        ");

        /*
         * -------------------------------------------------
         * TRIGGER - AFTER DELETE
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_ad`
            AFTER DELETE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    old_data
                )
                VALUES (
                    'transfer',
                    OLD.id,
                    'deleted',
                    JSON_OBJECT(
                        'wallet_sender', OLD.wallet_sender,
                        'wallet_receiver', OLD.wallet_receiver,
                        'amount', OLD.amount,
                        'status', OLD.status,
                        'idempotency_key', OLD.idempotency_key
                    )
                );
            END;
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        /*
         * -------------------------------------------------
         * DROP TRIGGERS
         * -------------------------------------------------
         */

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_ai`;
        ");

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_au`;
        ");

        $driver->execute("
            DROP TRIGGER IF EXISTS `trg_transfer_ad`;
        ");

        /*
         * -------------------------------------------------
         * DROP UNIQUE INDEX
         * -------------------------------------------------
         */

        $driver->execute("
            ALTER TABLE `transfer`
            DROP INDEX `uq_transfer_idempotency_key`;
        ");

        /*
         * -------------------------------------------------
         * DROP COLUMN
         * -------------------------------------------------
         */

        $driver->execute("
            ALTER TABLE `transfer`
            DROP COLUMN `idempotency_key`;
        ");

        /*
         * -------------------------------------------------
         * RECREATE ORIGINAL TRIGGER - AFTER INSERT
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_ai`
            AFTER INSERT ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    new_data
                )
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
         * -------------------------------------------------
         * RECREATE ORIGINAL TRIGGER - AFTER UPDATE
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_au`
            AFTER UPDATE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    old_data,
                    new_data
                )
                VALUES (
                    'transfer',
                    NEW.id,
                    IF(
                        OLD.status <> NEW.status,
                        'status_changed',
                        'updated'
                    ),
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
         * -------------------------------------------------
         * RECREATE ORIGINAL TRIGGER - AFTER DELETE
         * -------------------------------------------------
         */

        $driver->execute("
            CREATE TRIGGER `trg_transfer_ad`
            AFTER DELETE ON `transfer`
            FOR EACH ROW
            BEGIN
                INSERT INTO system_logs (
                    entity,
                    entity_id,
                    action,
                    old_data
                )
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
};