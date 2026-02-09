<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\MakeDeposit;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class MakeDepositInput
{
    #[OA\Property(example: 1)]
    private int $walletId;
    
    #[OA\Property(example:1000)]
    private int $value;

    public function __construct(
        int $walletId,
        int $value
    ) {
        $this->walletId = $walletId;
        $this->value = $value;
    }

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}