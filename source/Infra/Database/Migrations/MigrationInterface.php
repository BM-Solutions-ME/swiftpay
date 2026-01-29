<?php

namespace Source\Infra\Database\Migrations;


interface MigrationInterface
{
    public function id(): string; // ex: 20250101_create_users_table

    public function up(MigrationDriverInterface $driver): void;

    public function down(MigrationDriverInterface $driver): void;
}
