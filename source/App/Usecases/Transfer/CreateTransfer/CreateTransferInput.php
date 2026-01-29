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

    /**
     * @param array<string, mixed> $data
     * @return self
    */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data["wallet_payee"],
            (int) $data["payee"],
            (int) $data["wallet_payer"],
            (int) $data["value"]
        );
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