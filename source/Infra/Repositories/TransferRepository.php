<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Override;
use Source\Domain\Entities\Transfer;
use Source\Domain\Repositories\TransferRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class TransferRepository implements TransferRepositoryInterface
{
    public function idempotencyKeyValidation(Transfer $data): bool
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        $transferDuplicated = $repo->query(Transfer::class)
            ->where("wallet_sender", "=", $data->getWalletSender())
            ->andWhere("wallet_receiver", "=", $data->getWalletReceiver())
            ->andWhere("idempotency_key", "=", $data->getIdempotencyKey())
            ->get();
        
        if(!empty($transferDuplicated)) {
            return false;
        }

        return true;
    }

    public function execute(Transfer $data): Transfer
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var Transfer $transfer */
        $transfer = $repo->save($data);
        return $transfer;
    }
}
