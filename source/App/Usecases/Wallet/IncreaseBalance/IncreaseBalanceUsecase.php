<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\IncreaseBalance;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class IncreaseBalanceUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    public function handle(int $walletId, int $value): Wallet
    {
        return $this->repository->increaseBalance($walletId, $value);
    }
}