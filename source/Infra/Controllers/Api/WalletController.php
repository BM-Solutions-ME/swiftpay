<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\Wallet\GetBalanceService;
use Source\App\Services\Wallet\GetWalletService;
use Source\App\Services\Wallet\ListUserWalletsService;
use Source\App\Services\Wallet\NewWalletService;
use Source\Framework\Support\InputSanitizer;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\WalletRepository;
use Source\Presentation\Http\ApiResponse;

/**
 *
 */
class WalletController extends Api
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function all(array $data): void
    {
        try {
            $dataFiltered = InputSanitizer::sanitize($data);
            $dataFiltered["user_id"] = (!empty($dataFiltered["user_id"])
                ? $dataFiltered["user_id"] : $this->user->getId());
            ApiResponse::success((new ListUserWalletsService(new WalletRepository))->handle($dataFiltered));
        } catch (\Throwable $e) {
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function store(array $data): void
    {
        try {
            $dataFiltered = InputSanitizer::sanitize($data);
            ApiResponse::success((new GetWalletService(new WalletRepository))->handle($dataFiltered)->toArray());
        } catch (\Throwable $e) {
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function balance(array $data): void
    {
        try {
            $dataFiltered = InputSanitizer::sanitize($data);
            $dataFiltered["user_id"] = (!empty($dataFiltered["user_id"])
                ? $dataFiltered["user_id"] : $this->user->getId());
            ApiResponse::success(["balance" => (new GetBalanceService(new WalletRepository))->handle($dataFiltered)]);
        } catch (\Throwable $e) {
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     * return void
    */
    public function create(array $data): void
    {
        try {
            $dataFiltered = InputSanitizer::sanitize($data);
            $dataFiltered["user_id"] = (!empty($dataFiltered["user_id"]) ? $dataFiltered["user_id"] : $this->user->getId());
            $newWallet = (new NewWalletService(new WalletRepository))->handle($dataFiltered);
            ApiResponse::success($newWallet->toArray());
        } catch (\Throwable $e) {
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }
}