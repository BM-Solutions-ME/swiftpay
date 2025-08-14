<?php

declare(strict_types=1);

namespace Source\Infra\Repositories\Strategy;

use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Infra\Repositories\Contract\QueryableRepositoryInterface;
use Source\Infra\Repositories\Contract\RepositoryHandlerInterface;
use Source\Infra\Repositories\Handler\FluentQueryBuilder;

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