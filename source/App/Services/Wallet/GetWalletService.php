<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

/**
 *
 */
final class GetWalletService
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
        return $this->repository->store((int) $data["wallet_id"]);
    }
}