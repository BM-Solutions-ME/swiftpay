<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\CreateTransfer;

use Exception;
use Source\Domain\Entities\Transfer;
use Source\Domain\Enum\TransferStatusEnum;
use Source\Domain\Repositories\TransferRepositoryInterface;

final class CreateTransferUsecase
{
    /**
     * @param TransferRepositoryInterface $repository
     */
    public function __construct(
        private readonly TransferRepositoryInterface $repository
    ) {
    }

    /**
     * @param CreateTransferInput $input
     * @return CreateTransferOutput
     */
    public function handle(CreateTransferInput $input): CreateTransferOutput
    {
        $transfer = new Transfer();
        $transfer->setIdempotencyKey($input->getIdempotencyKey());
        $transfer->setWalletReceiver((int) $input->getWalletPayee());
        $transfer->setWalletSender((int) $input->getWalletPayer());
        $transfer->setAmount($input->getValue());
        $transfer->setStatus(TransferStatusEnum::COMPLETED);

        if (!$this->repository->idempotencyKeyValidation($transfer)) {
            throw new Exception("Esta transferência já está sendo processada. Por favor, aguarde o resultado final antes de tentar novamente.");
        }

        $createTransfer = $this->repository->execute($transfer);

        return new CreateTransferOutput([
            "id" => $createTransfer->getId(),
            "walletReceiverId" => $createTransfer->getWalletReceiver(),
            "walletSenderId" => $createTransfer->getWalletSender(),
            "status" => $createTransfer->getStatus()->value,
            "value" => $createTransfer->getAmount(),
            "createdAt" => $createTransfer->getCreatedAt()
        ]);
    }
}
