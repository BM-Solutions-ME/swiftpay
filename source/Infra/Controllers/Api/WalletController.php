<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\Wallet\DepositService;
use Source\App\Services\Wallet\GetBalanceService;
use Source\App\Services\Wallet\GetWalletService;
use Source\App\Services\Wallet\ListUserWalletsService;
use Source\App\Services\Wallet\NewWalletService;
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
            $data["user_id"] = (!empty($data["user_id"])
                ? $data["user_id"] : $this->user->getId());
            ApiResponse::success((new ListUserWalletsService(new WalletRepository))->handle($data));
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
            $wallet = (new GetWalletService(new WalletRepository))->handle($data)->toArray();
            $wallet["balance"] = toCurrency($wallet["balance"]);
            ApiResponse::success($wallet);
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
            $data["user_id"] = (!empty($data["user_id"])
                ? $data["user_id"] : $this->user->getId());
            ApiResponse::success(["balance" => (new GetBalanceService(new WalletRepository))->handle($data)]);
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
            $data["user_id"] = (!empty($data["user_id"]) ? $data["user_id"] : $this->user->getId());
            $newWallet = (new NewWalletService(new WalletRepository))->handle($data);
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

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function deposit(array $data): void
    {
        try {
            $wallet = (new DepositService(new WalletRepository))->handle($data)->toArray();
            $wallet["balance"] = toCurrency($wallet["balance"]);
            ApiResponse::success($wallet);
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