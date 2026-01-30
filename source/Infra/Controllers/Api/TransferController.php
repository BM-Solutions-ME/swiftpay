<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Transfer\CreateTransfer\CreateTransferInput;
use Source\App\Usecases\Transfer\ExecuteTransfer\ExecuteTransferUsecase;
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
     * @param CreateTransferInput $data
     * @return void
    */
    public function doTransfer(CreateTransferInput $data): void
    {
        try {
            Transaction::open();
            $transferExecute = (new ExecuteTransferUsecase(
                new TransferRepository(),
                new WalletRepository(),
                new UserRepository(),
                new Http()
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