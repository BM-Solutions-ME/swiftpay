<?php

declare(strict_types=1);

namespace Source\App\Services\Transfer;

use Exception;
use Source\App\Contracts\CurlRequestInterface;
use Source\Domain\Repositories\AuthRepositoryInterface;
use Source\Domain\Repositories\TransferRepositoryInterface;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class TransferExecuteService
{
    public function __construct(
        private readonly AuthRepositoryInterface     $authRepo,
        private readonly TransferRepositoryInterface $transferRepo,
        private readonly WalletRepositoryInterface   $walletRepo,
        private readonly CurlRequestInterface $http
    ) {}

    /**
     * @param array<string, mixed> $data
    */
    public function handle(array $data): array
    {
        $validateOperation = $this->http->setBaseUrl("https://util.devi.tools/api/v2/authorize");
        $validateOperation->get();

        if ($validateOperation->getResponse()->status != "success") {
            throw new Exception("A tranferência não pode ser concluída pela operadora, 
                    tente novamente mais tarde.");
        }

        return [];
    }
}