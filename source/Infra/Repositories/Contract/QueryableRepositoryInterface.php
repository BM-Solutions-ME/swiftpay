<?php

declare(strict_types=1);

namespace Source\Infra\Repositories\Contract;

use Source\Infra\Repositories\Handler\FluentQueryBuilder;

interface QueryableRepositoryInterface
{
    public function query(string $entityClass, string $alias): FluentQueryBuilder;
}