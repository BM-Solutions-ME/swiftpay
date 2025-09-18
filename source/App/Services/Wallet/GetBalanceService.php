<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\Domain\Repositories\WalletRepositoryInterface;

/**
 *
 */
final class GetBalanceService
{
    /**
     * @param WalletRepositoryInterface $repository
     */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return int
     */
    public function handle(array $data): int
    {
        if (!empty($data["wallet_id"])) {
            return $this->repository->balance((int) $data["wallet_id"]);
        }

        return $this->repository->balanceAll((int) $data["user_id"]);
    }
}