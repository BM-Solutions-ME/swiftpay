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
     * @param array<string, mixed> $data
     * @return list<array<string, mixed>>
    */
    public function all(array $data): array
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var array<int, mixed> $wallets */
        $wallets = $repo->query(Wallet::class)
            ->where("user_id", "=", $data["user_id"])
            ->get(true);

        $response = [];
        if (!empty($wallets)) {
            /** @var Wallet $wallet */
            foreach ($wallets as $wallet) {
                $response[] = $wallet->toArray();
            }
        }

        return $response;
    }

    /**
     * @param int $walletId
     * @return Wallet
    */
    public function store(int $walletId): Wallet
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var Wallet|null $wallet */
        $wallet = $repo->query(Wallet::class)
            ->where("id", "=", $walletId)
            ->get();

        if (empty($wallet)) {
            throw new \Exception("A carteira não existe ou foi removida recentemente.");
        }

        return $wallet;
    }

    /**
     * @param int $userId
     * @return int
     */
    public function balanceAll(int $userId): int
    {
        $wallets = $this->all(["user_id" => $userId]);

        if (empty($wallets)) {
            return 0;
        }

        $balance = 0;
        foreach ($wallets as $wallet) {
            $balance += $wallet["balance"];
        }

        return $balance;
    }

    /**
     * @param int $walletId
     * @return int
    */
    public function balance(int $walletId): int
    {
        $wallet = $this->store($walletId);
        return $wallet->getBalance();
    }

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

    /**
     * @param int $walletId
     * @param int $value
     * @return Wallet
    */
    public function increaseBalance(int $walletId, int $value): Wallet
    {
        $repo = (new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance())));
        /** @var Wallet|null $wallet */
        $wallet = $repo->query(Wallet::class)->where("id", "=", $walletId)->get();

        if (empty($wallet)) {
            throw new \Exception("A carteira não existe ou foi removida recentemente.");
        }

        $wallet->setBalance($wallet->getBalance() + $value);
        /** @var Wallet $walletUpdated */
        $walletUpdated = $repo->save($wallet);
        return $walletUpdated;
    }

    /**
     * @param int $walletId
     * @param int $value
     * @return Wallet
     */
    public function decreaseBalance(int $walletId, int $value): Wallet
    {
        $repo = (new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance())));
        /** @var Wallet|null $wallet */
        $wallet = $repo->query(Wallet::class)->where("id", "=", $walletId)->get();

        if (empty($wallet)) {
            throw new \Exception("A carteira não existe ou foi removida recentemente.");
        }

        if ($wallet->getBalance() < $value) {
            throw new \Exception("A carteira não possui saldo suficiente.");
        }

        $wallet->setBalance($wallet->getBalance() - $value);
        /** @var Wallet $walletUpdated */
        $walletUpdated = $repo->save($wallet);
        return $walletUpdated;
    }
}