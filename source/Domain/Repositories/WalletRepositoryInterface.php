<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\App\Usecases\Wallet\NewWallet\NewWalletOutputData;
use Source\Domain\Entities\Wallet;

/**
 *
 */
interface WalletRepositoryInterface
{
    /**
     * @param array<string, mixed> $data
     * @return list<array<string, mixed>>
    */
    public function all(array $data): array;

    /**
     * @param int $walletId
     * @return Wallet
     */
    public function store(int $walletId): Wallet;

    /**
     * @param int $userId
     * @return int
     */
    public function balanceAll(int $userId): int;

    /**
     * @param int $walletId
     * @return int
     */
    public function balance(int $walletId): int;
    /**
     * @param Wallet $newWallet
     * @return NewWalletOutputData
    */
    public function create(Wallet $newWallet): NewWalletOutputData;

    /**
     * @param int $walletId
     * @param int $value
     * @return Wallet
    */
    public function increaseBalance(int $walletId, int $value): Wallet;

    /**
     * @param int $walletId
     * @param int $value
     * @return Wallet
    */
    public function decreaseBalance(int $walletId, int $value): Wallet;
}