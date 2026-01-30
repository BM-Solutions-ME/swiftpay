<?php

use Source\Infra\Database\Migrations\MigrationInterface;
use Source\Infra\Database\Migrations\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "20260130192410";
    }

    public function up(MigrationDriverInterface $driver): void
    {
        /*
         |--------------------------------------------------
         | FIRST USER INSERT
         |--------------------------------------------------
        */
        $driver->execute("INSERT INTO users 
            (type, `first_name`, `last_name`, `document`, email, password, level, status) 
            VALUES ('F', 'Bruno', 'Mota', '079.641.890-00', 'bruno.mota@gmail.com', '12345678', 10, 'active');
        ");
    }

    public function down(MigrationDriverInterface $driver): void
    {
        //
    }
};