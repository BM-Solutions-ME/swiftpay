<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\ListWalletsByUserId;

use Source\Domain\Repositories\WalletRepositoryInterface;

final class ListWalletsByUserIdUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param int $userId
     * @return list<array<string, mixed>>
    */
    public function handle(int $userId): array
    {
        return $this->repository->all($userId);
    }
}