<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\App\Usecases\Wallet\NewWallet\NewWalletOutputData;
use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

/**
 *
 */
final class WalletRepository implements WalletRepositoryInterface
{
    /**
     * @param Wallet $newWallet
     * @return NewWalletOutputData
     */
    public function create(Wallet $newWallet): NewWalletOutputData
    {
        $repo = (new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance())));
        /** @var Wallet $walletRegister */
        $walletRegister = $repo->save($newWallet);
        return new NewWalletOutputData($walletRegister->toArray());
    }
}