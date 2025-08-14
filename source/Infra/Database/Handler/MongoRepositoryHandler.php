<?php

namespace Source\Infra\Database\Handler;

use Source\Framework\Support\Orm\Contract\RepositoryHandlerInterface;

class MongoRepositoryHandler implements RepositoryHandlerInterface
{
    public function find(string $entityClass, mixed $id): object|null {
        // Equivalente para MongoDB
        return null;
    }

    public function save(object $entity): object {
        // Inserção ou atualização no Mongo
        return new \stdClass();
    }

    public function delete(object $entity): void {
        // Remove documento
    }
}