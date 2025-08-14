<?php

declare(strict_types=1);

namespace Source\Framework\Support\Orm\Strategy;

use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Framework\Support\Orm\Contract\QueryableRepositoryInterface;
use Source\Framework\Support\Orm\Contract\RepositoryHandlerInterface;
use Source\Framework\Support\Orm\Handler\FluentQueryBuilder;

readonly class RepositoryStrategy
{
    public function __construct(
        private RepositoryHandlerInterface $handler
    ) {}

    public function find(string $entityClass, mixed $id): object|null {
        return $this->handler->find($entityClass, $id);
    }

    /*
     * Exemplo:
     *
     * $users = $repository
        ->query(User::class)
        ->where('age', '>=', 18)
        ->andWhere('name', 'LIKE', '%Bruno%')
        ->get();
     * */
    public function query(string $entityClass, string $alias = ''): FluentQueryBuilder
    {
        if (!$this->handler instanceof QueryableRepositoryInterface) {
            throw new \RuntimeException("This repository does not support fluent queries.");
        }

        return $this->handler->query($entityClass, $alias);
    }

    /**
     * @param PersistableEntityInterface $entity
     * @return object|null
    */
    public function save(PersistableEntityInterface $entity): object|null {
        return $this->handler->save($entity);
    }

    public function delete(object $entity): void {
        $this->handler->delete($entity);
    }
}