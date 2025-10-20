<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\MakeATransfer;

use Exception;
use Source\Domain\Entities\Transfer;
use Source\Domain\Enum\TransferStatusEnum;
use Source\Domain\Repositories\TransferRepositoryInterface;

/**
 *
 */
final class MakeTransferUsecase
{
    /**
     * @param TransferRepositoryInterface $repository
     */
    public function __construct(
        private readonly TransferRepositoryInterface $repository
    ) {}

    /**
     * @param MakeTransferInput $input
     * @return MakeTransferOutput
     */
    public function handle(MakeTransferInput $input): MakeTransferOutput
    {
        if (!empty($input->getWalletSender()->getCompanyId())) {
            throw new Exception("Somente pessoa física pode realizar transferências.");
        }

        if ($input->getWalletSender()->getId() == $input->getWalletReceiver()->getId()) {
            throw new Exception("Você não pode realizar transferências para sua própria carteira.");
        }

        $transfer = new Transfer;
        $transfer->setWalletReceiver((int) $input->getWalletReceiver()->getId());
        $transfer->setWalletSender((int) $input->getWalletSender()->getId());
        $transfer->setAmount($input->getValue());
        $transfer->setStatus(TransferStatusEnum::COMPLETED);

        $makeTransfer = $this->repository->execute($transfer);

        return new MakeTransferOutput([
            "id" => $makeTransfer->getId(),
            "payerName" => $input->getUserSender()->getFirstName(),
            "payeeName" => $input->getUserReceiver()->getFirstName(),
            "value" => $makeTransfer->getAmount(),
            "createdAt" => $makeTransfer->getCreatedAt()
        ]);
    }
}