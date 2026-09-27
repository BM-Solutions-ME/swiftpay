<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\CreateTransfer;

use OpenApi\Attributes as OA;

#[ OA\Schema]
final class CreateTransferInput
{
    #[OA\Property(example: "123e4567-e89b-12d3-a456-426614174000")]
    private readonly string $idempotencyKey;

    #[OA\Property(example: 2)]
    private readonly int $walletPayee;

    #[OA\Property(example: 2)]
    private readonly int $userPayee;

    #[OA\Property(example: 1)]
    private readonly int $walletPayer;

    #[OA\Property(example: 1000)]
    private readonly int $value;

    public function __construct(
        string $idempotencyKey,
        int $walletPayee,
        int $userPayee,
        int $walletPayer,
        int $value
    ) {
        $this->idempotencyKey = $idempotencyKey;
        $this->walletPayee = $walletPayee;
        $this->userPayee = $userPayee;
        $this->walletPayer = $walletPayer;
        $this->value = $value;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function getWalletPayee(): int
    {
        return $this->walletPayee;
    }

    public function getUserPayee(): int
    {
        return $this->userPayee;
    }

    public function getWalletPayer(): int
    {
        return $this->walletPayer;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
