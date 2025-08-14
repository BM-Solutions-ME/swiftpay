<?php

declare(strict_types=1);

namespace Source\Infra\Repositories\Contract;

use Source\Domain\Contracts\PersistableEntityInterface;

interface RepositoryHandlerInterface
{
    public function find(string $entityClass, mixed $id): object|null;
    public function save(PersistableEntityInterface $entity): object|null;
    public function delete(object $entity): void;
}