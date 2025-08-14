<?php

namespace Source\Framework\Core;

use PDO;
use PDOException;

/**
 * Class Connect [ Singleton Pattern ]
 *
 * @package Source\Core
 */
class Connect
{
    /** @var PDO */
    private static PDO $instance;

    /**
     * @param bool $auto_commit
     * @return PDO
     */
    public static function getInstance(bool $auto_commit = true): PDO
    {
        if (!empty(Transaction::get())) {
            self::$instance = Transaction::get();
        }

        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . CONF_DB_HOST . ";dbname=" . CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASS,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL,
                        PDO::ATTR_TIMEOUT => 10,
                        PDO::ATTR_AUTOCOMMIT => $auto_commit
                    ]
                );
            } catch (PDOException $exception) {
                //var_dump($exception);
                //return null;
                $json["errors"] = [
                    "code" => 400,
                    "type" => "connection_refused",
                    "message" => "Não foi possível conectar ao banco de dados. [{$exception->getMessage()}]"
                ];
                echo "<pre>";
                echo json_encode($json, JSON_PRETTY_PRINT);
                echo "</pre>";
                die;
                //redirect("/ops/problemas");
            }
        }

        return self::$instance;
    }

    /**
     * Connect constructor.
     */
    final private function __construct()
    {
    }

    /**
     * Connect clone.
     */
    final public function __clone()
    {
    }
}
