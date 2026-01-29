<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\DecreaseBalance;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class DecreaseBalanceUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    public function handle(int $walletSenderId, int $value): Wallet
    {
        return $this->repository->decreaseBalance($walletSenderId, $value);
    }
}