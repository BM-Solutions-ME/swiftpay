<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetWalletById;

final class GetWalletByIdInput
{
    public function __construct(
        private readonly int $walletId
    ) {}

    public function getWalletId(): int
    {
        return $this->walletId;
    }
}