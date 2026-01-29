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
    ) {}

    /**
     * @param CreateTransferInput $input
     * @return CreateTransferOutput
     */
    public function handle(CreateTransferInput $input): CreateTransferOutput
    {
        $transfer = new Transfer;
        $transfer->setWalletReceiver((int) $input->getWalletPayee());
        $transfer->setWalletSender((int) $input->getWalletPayer());
        $transfer->setAmount($input->getValue());
        $transfer->setStatus(TransferStatusEnum::COMPLETED);

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