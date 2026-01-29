<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetBalance;

final class GetBalanceInput
{
    public function __construct(
        private readonly string $filterBy,
        private readonly int $id
    ) {}

    public function getFilterBy(): string
    {
        return $this->filterBy;
    }

    public function getId(): int
    {
        return $this->id;
    }
}