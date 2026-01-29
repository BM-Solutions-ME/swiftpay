#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';


// Validação básica
if ($argc < 2) {
    echo "Usage:\n";
    echo "  php bin/make:migration.php migration_name --driver=mysql\n";
    exit(1);
}

$migrationName = $argv[1];

$driverFlag = $argv[2] ?? null;
if (!$driverFlag || !str_contains($driverFlag, '--driver=')) {
    echo "You must specify the driver:\n";
    echo "  php bin/make:migration.php create_users_table --driver=mysql\n";
    exit(1);
}

$driver = explode('=', $driverFlag)[1];

// Gera o arquivo
$maker = new \Source\Infra\Database\Migrations\MigrationMaker();
$path = $maker->make($migrationName, $driver);

echo "Migration created: $path\n";
