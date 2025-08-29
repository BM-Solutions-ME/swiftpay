<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

/**
 *
 */
final class NewWalletInputData
{
    /**
     * @param int $userId
     * @param int|null $companyId
     */
    public function __construct(
        private readonly int $userId,
        private readonly ?int $companyId
    ) {}

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }
}