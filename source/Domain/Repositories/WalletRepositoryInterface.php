<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\Wallet;

/**
 *
 */
interface WalletRepositoryInterface
{
    /**
     * @param int $userId
     * @return array<int, Wallet>
    */
    public function all(int $userId): array;
    public function store(int $walletId): Wallet;
    public function balanceAll(int $userId): int;
    /** @return int */
    public function balance(int $walletId): int;
    /**
     * @param Wallet $newWallet
     * @return Wallet
    */
    public function create(Wallet $newWallet): Wallet;

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