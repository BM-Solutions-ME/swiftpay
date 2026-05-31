<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetWalletByName;

use OpenApi\Attributes as OA;

#[ OA\Schema]
final class GetWalletByNameInput
{
    #[OA\Property(example: 1)]
    private ?int $userId;

    #[OA\Property(example: 'Carteira 01')]
    private readonly string $walletTitleSearch;

    /*
     * @param int|null $userId
     * @param int @walletId
    */
    public function __construct(
        ?int $userId,
        string $walletTitleSearch
    )
    {
        $this->userId = $userId;
        $this->walletTitleSearch = $walletTitleSearch;
    }

    /*
     * @return int|null
    */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
    */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /*
     * @return string
    */
    public function getWalletTitleSearch(): string
    {
        return $this->walletTitleSearch;
    }
}