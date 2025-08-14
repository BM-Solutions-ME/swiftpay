<?php

declare(strict_types=1);

namespace Source\Framework\Support\Orm\Contract;

use Source\Framework\Support\Orm\Handler\FluentQueryBuilder;

interface QueryableRepositoryInterface
{
    public function query(string $entityClass, string $alias): FluentQueryBuilder;
}