<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

use OpenApi\Attributes as OA;

/**
 *
 */
#[ OA\Schema]
final class NewWalletInputData
{
    #[OA\Property(example: 1)]
    private ?int $userId;
    
    #[OA\Property(example: "Carteira 01")]
    private readonly string $title;

    #[OA\Property(example:1)]
    private readonly ?int $companyId;

    /**
     * @param ?int $userId
     * @param string $title
     * @param int|null $companyId
     */
    public function __construct(
        ?int $userId,
        string $title,
        ?int $companyId
    ) {
        $this->userId = (!empty($userId) ? $userId : null);
        $this->title = $title;
        $this->companyId = (!empty($companyId) ? $companyId : null);
    }

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