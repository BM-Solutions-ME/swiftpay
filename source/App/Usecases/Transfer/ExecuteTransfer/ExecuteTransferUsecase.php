<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\ExecuteTransfer;

use Exception;
use Source\App\Contracts\TransferAuthorizer;
use Source\App\Usecases\Transfer\CreateTransfer\CreateTransferInput;
use Source\App\Usecases\Transfer\CreateTransfer\CreateTransferUsecase;
use Source\App\Usecases\Wallet\DecreaseBalance\DecreaseBalanceUsecase;
use Source\App\Usecases\Wallet\IncreaseBalance\IncreaseBalanceUsecase;
use Source\Domain\Entities\User;
use Source\Domain\Repositories\TransferRepositoryInterface;
use Source\Domain\Repositories\WalletRepositoryInterface;
use Source\Domain\Repositories\UserRepositoryInterface;

final class ExecuteTransferUsecase
{
    public function __construct(
        private readonly TransferRepositoryInterface $transferRepo,
        private readonly WalletRepositoryInterface $walletRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly TransferAuthorizer $transferAuthorizer
    ) {}

    public function handle(User $payer, CreateTransferInput $input): ExecuteTransferOutput
    {
        $payee = $this->userRepo->getUserById($input->getUserPayee());
        $walletPayee = $this->walletRepo->store($input->getWalletPayee());
        $walletPayer = $this->walletRepo->store($input->getWalletPayer());
        $value = $input->getValue();

        if (!empty($walletPayer->getCompanyId())) {
            throw new Exception("Somente pessoa física pode realizar transferências.");
        }

        if ($walletPayer->getId() === $walletPayee->getId()) {
            throw new Exception("Você não pode realizar transferências para sua própria carteira.");
        }

        $executeTransfer = (new CreateTransferUsecase($this->transferRepo))->handle($input);

        (new DecreaseBalanceUsecase($this->walletRepo))->handle((int) $walletPayer->getId(), $value);
        (new IncreaseBalanceUsecase($this->walletRepo))->handle((int) $walletPayee->getId(), $value);

        if (!$this->transferAuthorizer->authorize()) {
            throw new Exception("A tranferência não pode ser concluída pela operadora, 
                    tente novamente mais tarde.");
        }

        return new ExecuteTransferOutput([
            "id" => $executeTransfer->getId(),
            "payerName" => $payer->getFirstName(),
            "payeeName" => $payee->getFirstName(),
            "value" => $executeTransfer->getValue(),
            "status" => $executeTransfer->getStatus(),
            "createdAt" => $executeTransfer->getCreatedAt()
        ]);
    }
}