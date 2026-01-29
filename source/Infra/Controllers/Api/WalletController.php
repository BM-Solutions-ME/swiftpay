<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Wallet\GetBalance\GetBalanceInput;
use Source\App\Usecases\Wallet\GetBalance\GetBalanceUsecase;
use Source\App\Usecases\Wallet\GetWalletById\GetWalletByIdUsecase;
use Source\App\Usecases\Wallet\ListWalletsByUserId\ListWalletsByUserIdUsecase;
use Source\App\Usecases\Wallet\MakeDeposit\MakeDepositInput;
use Source\App\Usecases\Wallet\MakeDeposit\MakeDepositUsecase;
use Source\App\Usecases\Wallet\NewWallet\NewWalletInputData;
use Source\App\Usecases\Wallet\NewWallet\NewWalletUsecase;
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
            $listWalletsUser = (new ListWalletsByUserIdUsecase(new WalletRepository))
                ->handle($data["user_id"]);
            ApiResponse::success($listWalletsUser);
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
            $wallet = (new GetWalletByIdUsecase(new WalletRepository))->handle((int) $data["wallet_id"])->toArray();
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
            $filterBy = (!empty($data["wallet_id"]) ? "wallet" : "user");
            $id = ($filterBy === "user" ? $this->user->getId() : $data["wallet_id"]);
            $input = new GetBalanceInput($filterBy, $id);
            ApiResponse::success(["balance" => (new GetBalanceUsecase(new WalletRepository))->handle($input)]);
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
            $input = new NewWalletInputData(
                $data["user_id"],
                $data["title"],
                $data["company_id"] ?? null
            );
            $newWallet = (new NewWalletUsecase(new WalletRepository()))->handle($input);
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
            $input = new MakeDepositInput($data["wallet_id"], $data["value"]);
            $wallet = (new MakeDepositUsecase(new WalletRepository()))->handle($input)->toArray();
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