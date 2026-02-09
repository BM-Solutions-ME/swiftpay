<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\ListWalletsByUserId;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class ListWalletsByUserIdInput
{
    #[OA\Property(example: 1)]
    private ?int $userId;

    public function __construct(
        ?int $userId
    ) {}

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }
}