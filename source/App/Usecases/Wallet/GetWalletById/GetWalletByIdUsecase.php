<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\GetWalletById;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class GetWalletByIdUsecase
{
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    public function handle(GetWalletByIdInput $input): Wallet
    {
        return $this->repository->store($input->getWalletId());
    }
}