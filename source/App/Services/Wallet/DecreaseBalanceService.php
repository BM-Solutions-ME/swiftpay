<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class DecreaseBalanceService
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return Wallet
    */
    public function handle(array $data): Wallet
    {
        return $this->repository->decreaseBalance($data["wallet_id"], $data["value"]);
    }
}