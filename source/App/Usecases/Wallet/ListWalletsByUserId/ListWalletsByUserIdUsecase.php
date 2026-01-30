<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\ListWalletsByUserId;

use Source\App\Usecases\Wallet\ListWalletsByUserId\ListWalletsByUserIdInput;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class ListWalletsByUserIdUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param ListWalletsByUserIdInput $input
     * @return list<array<string, mixed>>
    */
    public function handle(ListWalletsByUserIdInput $input): array
    {
        return $this->repository->all((int) $input->getUserId());
    }
}