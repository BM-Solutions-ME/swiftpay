#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Source\Infra\Database\Migrations\MigrationRunner;
use Source\Infra\Database\Migrations\Drivers\MysqlMigrationDriver;
use Source\Framework\Core\Connect;

// Parse arguments
$driver = $argv[2] ?? null;
$action = $argv[1] ?? 'up';

if (!$driver) {
    echo "Usage: php bin/migrate.php up --driver=mysql\n";
    exit(1);
}

// select driver
$runner = match($driver) {
    "--driver=mysql" => new MigrationRunner(
            new MysqlMigrationDriver(Connect::getInstance()),
            "mysql"
    ),
    "--driver=mongodb" => "", // new MigrationRunner(new MongoDbMigrationDriver(new MongoDB\Client("mongodb://localhost:27017")), "mongodb")
    default => null
};

if (empty($runner)) {
    echo "Unknown driver: $driver\n";
    exit(1);
}

if ($action === "up") {
    $runner->runUp();
    echo "Migrations executed successfully\n";
} elseif ($action === "down") {
    $runner->runDown();
    echo "All migrations rolled back\n";
} else {
    echo "Unknown action: $action\n";
    exit(1);
}
