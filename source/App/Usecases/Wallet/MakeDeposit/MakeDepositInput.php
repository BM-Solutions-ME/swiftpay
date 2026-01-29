<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\MakeDeposit;

final class MakeDepositInput
{
    public function __construct(
        private int $walletId,
        private int $value
    ) {}

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}