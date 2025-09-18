<?php

declare(strict_types=1);

namespace Source\App\Services\Wallet;

use Source\App\Usecases\Wallet\NewWallet\NewWalletInputData;
use Source\App\Usecases\Wallet\NewWallet\NewWalletOutputData;
use Source\App\Usecases\Wallet\NewWallet\NewWalletUsecase;
use Source\Domain\Repositories\WalletRepositoryInterface;

/**
 *
 */
final class NewWalletService
{
    /**
     * @param WalletRepositoryInterface $repository
     */
    public function __construct(
        private readonly WalletRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return NewWalletOutputData
    */
    public function handle(array $data): NewWalletOutputData
    {
        $input = new NewWalletInputData(
            $data["user_id"],
            $data["title"],
            $data["company_id"] ?? null
        );

        return (new NewWalletUsecase($this->repository))->handle($input);
    }
}