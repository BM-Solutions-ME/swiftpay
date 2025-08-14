<?php

namespace Source\Infra\Repositories\Handler;

use Source\Infra\Repositories\Contract\RepositoryHandlerInterface;

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