<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\MakeDeposit;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class MakeDepositUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    public function handle(MakeDepositInput $input): Wallet
    {
        $wallet = $this->repository->store($input->getWalletId());
        return $this->repository->increaseBalance(
            $wallet->getId(), 
            ($wallet->getBalance() + $input->getValue())
        );
    }
}