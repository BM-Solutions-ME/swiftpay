<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

/**
 *
 */
final class NewWalletUsecase
{
    /**
     * @param WalletRepositoryInterface $repository
     */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param NewWalletInputData $input
     * @return NewWalletOutputData
     */
    public function handle(NewWalletInputData $input): NewWalletOutputData
    {
        $newWallet = new Wallet();
        $newWallet->setUserId($input->getUserId());
        $newWallet->setCompanyId($input->getCompanyId() ?? null);
        $newWallet->setTitle($input->getTitle());
        $newWallet->setBalance(0);

        return $this->repository->create($newWallet);
    }
}