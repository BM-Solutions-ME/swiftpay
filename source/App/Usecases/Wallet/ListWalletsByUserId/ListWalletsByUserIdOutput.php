<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\ListWalletsByUserId;

use Source\Domain\Entities\Wallet;

final class ListWalletsByUserIdOutput
{
    private array $response = [];

    /**
     * @param array<int, Wallet> $wallets
    */
    public function __construct(
        private readonly array $wallets
    ) {}

    /**
     * @return array<int, Wallet>
    */
    public function toArray(): array
    {
        if (!empty($this->wallets)) {
            /** @var Wallet $wallet */
            foreach ($this->wallets as $wallet) {
                $this->response[] = $wallet->toArray();
            }
        }

        return $this->response;
    }
}