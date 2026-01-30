<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\CreateTransfer;

final class CreateTransferInput
{
    public function __construct(
        private readonly int $walletPayee,
        private readonly int $userPayee,
        private readonly int $walletPayer,
        private readonly int $value
    ) {}

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