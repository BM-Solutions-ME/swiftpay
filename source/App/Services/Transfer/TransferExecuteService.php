<?php

declare(strict_types=1);

namespace Source\App\Services\Transfer;

use Exception;
use Source\App\Contracts\CurlRequestInterface;
use Source\App\Services\Wallet\DecreaseBalanceService;
use Source\App\Services\Wallet\IncreaseBalanceService;
use Source\App\Usecases\Transfer\MakeATransfer\MakeTransferInput;
use Source\App\Usecases\Transfer\MakeATransfer\MakeTransferOutput;
use Source\App\Usecases\Transfer\MakeATransfer\MakeTransferUsecase;
use Source\Domain\Entities\User;
use Source\Domain\Repositories\TransferRepositoryInterface;
use Source\Domain\Repositories\UserRepositoryInterface;
use Source\Domain\Repositories\WalletRepositoryInterface;

final class TransferExecuteService
{
    public function __construct(
        private readonly UserRepositoryInterface     $userRepo,
        private readonly TransferRepositoryInterface $transferRepo,
        private readonly WalletRepositoryInterface   $walletRepo,
        private readonly CurlRequestInterface $http
    ) {}

    /**
     * @param User $payer
     * @param array<string, mixed> $data
     * @return MakeTransferOutput
    */
    public function handle(User $payer, array $data): MakeTransferOutput
    {
        $payee = $this->userRepo->getUserById((int) $data["payee"]);
        $walletPayee = $this->walletRepo->store((int) $data["walletPayee"]);
        $walletPayer = $this->walletRepo->store((int) $data["walletPayer"]);
        $value = (int) $data["value"];

        $makeTransfer = (new MakeTransferUsecase($this->transferRepo))->handle(
            new MakeTransferInput($walletPayee, $payee, $walletPayer, $payer, $value)
        );

        (new DecreaseBalanceService($this->walletRepo))->handle([
            "wallet_id" => $walletPayer->getId(),
            "value" => $value
        ]);

        (new IncreaseBalanceService($this->walletRepo))->handle([
            "wallet_id" => $walletPayee->getId(),
            "value" => $value
        ]);

        $validateOperation = $this->http->setBaseUrl("https://util.devi.tools/api/v2/authorize");
        $validateOperation->get();

        if ($validateOperation->getResponse()->status != "success") {
            throw new Exception("A tranferência não pode ser concluída pela operadora, 
                    tente novamente mais tarde.");
        }

        return $makeTransfer;
    }
}