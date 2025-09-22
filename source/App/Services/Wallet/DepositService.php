<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

/**
 *
 */
final class DepositService
{
    /**
     * @param WalletRepositoryInterface $repository
     */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return Wallet
     */
    public function handle(array $data): Wallet
    {
        $wallet = $this->repository->store((int) $data["wallet_id"]);
        return $this->repository->increaseBalance((int) $wallet->getId(), ($wallet->getBalance() + (int) $data["value"]));
    }
}