<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

/**
 *
 */
final class NewWalletInputData
{
    /**
     * @param ?int $userId
     * @param string $title
     * @param int|null $companyId
     */
    public function __construct(
        private ?int $userId,
        private readonly string $title,
        private readonly ?int $companyId
    ) {}

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
    */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return ($this->companyId > 0 ? $this->companyId : null);
    }
}