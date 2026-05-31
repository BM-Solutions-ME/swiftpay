<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetWalletByName;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class GetWalletByNameUsecase
{
    /**
     * @param WalletRepositoryInterface $repository
    */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param GetWalletByNameInput $input
     * @return list<array<string, mixed>>
    */
    public function handle(GetWalletByNameInput $input): array
    {
        /** @var array<int, Wallet> $walletSearched */
        $walletSearched = $this->repository->getByName($input->getUserId(), $input->getWalletTitleSearch());
        return (new GetWalletByNameOutput($walletSearched))->toArray();
    }
}