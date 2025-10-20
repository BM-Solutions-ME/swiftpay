<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\MakeATransfer;

use Source\Domain\Entities\User;
use Source\Domain\Entities\Wallet;

final class MakeTransferInput
{
    public function __construct(
        private readonly Wallet $walletReceiver,
        private readonly User $userReceiver,
        private readonly Wallet $walletSender,
        private readonly User $userSender,
        private readonly int $value
    ) {}

    public function getWalletReceiver(): Wallet
    {
        return $this->walletReceiver;
    }

    public function getUserReceiver(): User
    {
        return $this->userReceiver;
    }

    public function getWalletSender(): Wallet
    {
        return $this->walletSender;
    }

    public function getUserSender(): User
    {
        return $this->userSender;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}