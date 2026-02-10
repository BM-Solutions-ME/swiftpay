<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetBalance;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class GetBalanceInput
{
    #[OA\Property(example:"wallet")]
    private readonly string $filterBy;

    #[OA\Property(example: 1)]
    private ?int $id;

    public function __construct(
        string $filterBy,
        ?int $id
    ) {
        $this->filterBy = $filterBy;
        $this->id = (!empty($id) ? $id : null);
    }

    public function getFilterBy(): string
    {
        return $this->filterBy;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}