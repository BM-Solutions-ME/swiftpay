<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetBalance;

use Source\Domain\Repositories\WalletRepositoryInterface;

final class GetBalanceUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    public function handle(GetBalanceInput $input): int
    {
        if ($input->getFilterBy() === "wallet") {
            return $this->repository->balance($input->getId());
        }

        return $this->repository->balanceAll($input->getId());
    }
}