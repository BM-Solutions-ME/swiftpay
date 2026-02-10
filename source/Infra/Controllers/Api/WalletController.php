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
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\WalletRepository;
use Source\Presentation\Http\ApiResponse;

use OpenApi\Attributes as OA;

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
    #[OA\Post(
        path: "/wallet/all",
        summary: "Find all wallet's user",
        tags: ["Wallet"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: ListWalletsByUserIdInput::class)
        ),
        responses: [
            new OA\Response(response: 200, description: "Wallet's user"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
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
    #[OA\Get(
        path: "/wallet/store/{walletId}",
        summary: "Get wallet by id",
        tags: ["Wallet"],
        parameters: [
            new OA\Parameter(
                name: "walletId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Wallet data")
        ]
    )]
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
    #[OA\Post(
        path: "/wallet/balance",
        summary: "Find wallet balance or total balance by user",
        tags: ["Wallet"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: GetBalanceInput::class)
        ),
        responses: [
            new OA\Response(response: 200, description: "Wallet balance"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
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
    #[OA\Post(
        path: "/wallet/create",
        summary: "Create a new wallet",
        tags: ["Wallet"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: NewWalletInputData::class)
        ),
        responses: [
            new OA\Response(response: 201, description: "Wallet created"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
    public function create(NewWalletInputData $data): void
    {
        try {
            if (empty($data->getUserId())) {
                $data->setUserId((int) $this->user->getId());
            }
            $newWallet = (new NewWalletUsecase(new WalletRepository()))->handle($data);
            ApiResponse::success($newWallet->toArray(), "CREATED", HttpStatusEnum::CREATED);
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
    #[OA\Post(
        path: "/wallet/deposit",
        summary: "Make a new deposit",
        tags: ["Wallet"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: MakeDepositInput::class)
        ),
        responses: [
            new OA\Response(response: 200, description: "Deposit made"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
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