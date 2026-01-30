<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Wallet\GetBalance\GetBalanceInput;
use Source\App\Usecases\Wallet\GetBalance\GetBalanceUsecase;
use Source\App\Usecases\Wallet\GetWalletById\GetWalletByIdInput;
use Source\App\Usecases\Wallet\GetWalletById\GetWalletByIdUsecase;
use Source\App\Usecases\Wallet\ListWalletsByUserId\ListWalletsByUserIdUsecase;
use Source\App\Usecases\Wallet\ListWalletsByUserId\ListWalletsByUserIdInput;
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
     * @param ListWalletsByUserIdInput $data
     * @return void
    */
    public function all(ListWalletsByUserIdInput $data): void
    {
        try {
            if (empty($data->getUserId())) {
                $data->setUserId($this->user->getId());
            }

            $listWalletsUser = (new ListWalletsByUserIdUsecase(new WalletRepository))
                ->handle($data);
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
     * @param GetWalletByIdInput $data
     * @return void
    */
    public function store(GetWalletByIdInput $data): void
    {
        try {
            $wallet = (new GetWalletByIdUsecase(new WalletRepository))->handle($data)->toArray();
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
     * @param GetBalanceInput $data
     * @return void
     */
    public function balance(GetBalanceInput $data): void
    {
        try {
            if (empty($data->getId())) {
                $data->setId((int) $this->user->getId());
            }
            ApiResponse::success(["balance" => (new GetBalanceUsecase(new WalletRepository))->handle($data)]);
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
     * @param NewWalletInputData $data
     * return void
    */
    public function create(NewWalletInputData $data): void
    {
        try {
            if (empty($data->getUserId())) {
                $data->setUserId((int) $this->user->getId());
            }
            $newWallet = (new NewWalletUsecase(new WalletRepository()))->handle($data);
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
     * @param MakeDepositInput $data
     * @return void
    */
    public function deposit(MakeDepositInput $data): void
    {
        try {
            $wallet = (new MakeDepositUsecase(new WalletRepository()))->handle($data)->toArray();
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