<?php

namespace Source\Infra\Database\Migrations;


class MigrationMaker
{
    public function make(string $name, string $driver): string
    {
        $timestamp = date('YmdHis');
        $fileName = "{$timestamp}_{$name}.php";

        $directory = __DIR__ . "/../../../../migrations/{$driver}";

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filePath = "{$directory}/{$fileName}";

        $content = $this->template($timestamp, $name);

        file_put_contents($filePath, $content);

        return $filePath;
    }

    private function template(string $id, string $name): string
    {
        return <<<PHP
<?php

use Source\\Infra\\Database\\Migrations\\MigrationInterface;
use Source\\Infra\\Database\\Migrations\\MigrationDriverInterface;

return new class implements MigrationInterface {

    public function id(): string
    {
        return "{$id}";
    }

    public function up(MigrationDriverInterface \$driver): void
    {
        \$driver->execute("
            -- TODO: implement migration up()
            -- Example:
            -- CREATE TABLE users (
            --     id VARCHAR(36) PRIMARY KEY,
            --     name VARCHAR(255),
            --     email VARCHAR(255) UNIQUE,
            --     created_at DATETIME
            -- );
        ");
    }

    public function down(MigrationDriverInterface \$driver): void
    {
        \$driver->execute("
            -- TODO: implement migration down()
            -- Example:
            -- DROP TABLE users;
        ");
    }
};
PHP;
    }
}
