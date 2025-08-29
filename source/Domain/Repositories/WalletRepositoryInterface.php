<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\App\Usecases\Wallet\NewWallet\NewWalletOutputData;
use Source\Domain\Entities\Wallet;

interface WalletRepositoryInterface
{
    /**
     * @param Wallet $newWallet
     * @return NewWalletOutputData
    */
    public function create(Wallet $newWallet): NewWalletOutputData;
}