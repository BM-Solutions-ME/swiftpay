<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\Domain\Repositories\WalletRepositoryInterface;

final class ListUserWalletsService
{
    /**
     * @param WalletRepositoryInterface $repository
     */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return list<array<string, mixed>>
    */
    public function handle(array $data): array
    {
        return $this->repository->all($data);
    }
}