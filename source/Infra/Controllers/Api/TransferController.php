<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\Transfer\TransferExecuteService;
use Source\Framework\Core\Transaction;
use Source\Framework\Support\Http;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\TransferRepository;
use Source\Infra\Repositories\UserRepository;
use Source\Infra\Repositories\WalletRepository;
use Source\Presentation\Http\ApiResponse;

final class TransferController extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function doTransfer(array $data): void
    {
        try {
            Transaction::open();
            $transferExecute = (new TransferExecuteService(
                new UserRepository(),
                new TransferRepository,
                new WalletRepository,
                new Http
            ))->handle($this->user, $data);
            Transaction::close();
            ApiResponse::success($transferExecute->toArray());
        } catch (\Throwable $e) {
            Transaction::rollback();

            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }
}