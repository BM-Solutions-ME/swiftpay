<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\Transfer\TransferExecuteService;
use Source\Framework\Core\Transaction;
use Source\Framework\Support\Http;
use Source\Framework\Support\InputSanitizer;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\AuthRepository;
use Source\Infra\Repositories\TransferRepository;
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
            $dataFiltered = InputSanitizer::sanitize($data);
            Transaction::open();
            $transferExecute = (new TransferExecuteService(
                new AuthRepository,
                new TransferRepository,
                new WalletRepository,
                new Http
            ))->handle($dataFiltered);
            Transaction::close();
            ApiResponse::success($transferExecute);
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