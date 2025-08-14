<?php

namespace Source\Framework\Core;

use PDO;

class Transaction
{
    private static PDO|null $conn = null;

    public static function open(): void
    {
        //print_r("TRANSACTION OPEN\n");
        self::$conn = Connect::getInstance(false);
        if (!empty(self::$conn)) {
            self::$conn->beginTransaction();
        }
    }

    public static function rollback(): void
    {
        if (self::$conn) {
            //print_r("ROLLBACK\n\n");
            self::$conn->rollBack();
        }
    }

    public static function get(): ?PDO
    {
        if (self::$conn) {
            return self::$conn;
        }

        return null;
    }

    public static function close(): void
    {
        if (self::$conn) {
            //print_r("CLOSE AND COMMIT\n\n");
            self::$conn->commit();
            self::$conn = null;
        }
    }
}
