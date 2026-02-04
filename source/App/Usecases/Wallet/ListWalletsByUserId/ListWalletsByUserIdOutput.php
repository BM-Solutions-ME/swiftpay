<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\ListWalletsByUserId;

use Source\Domain\Entities\Wallet;

final class ListWalletsByUserIdOutput
{
    /**
     * @param array<int, Wallet> $wallets
    */
    public function __construct(
        private readonly array $wallets
    ) {}

    /**
     * @return list<array<string, mixed>>
    */
    public function toArray(): array
    {
        $response = [];
        if (!empty($this->wallets)) {
            /** @var Wallet $wallet */
            foreach ($this->wallets as $wallet) {
                $response[] = $wallet->toArray();
            }
        }

        return $response;
    }
}