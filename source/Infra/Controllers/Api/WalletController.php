<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\NewWalletService;
use Source\Domain\Http\ApiResponse;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\WalletRepository;

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
     * return void
    */
    public function create(array $data): void
    {
        try {
            $data["user_id"] = (!empty($data["user_id"]) ? $data["user_id"] : $this->user->getId());
            $newWallet = (new NewWalletService(new WalletRepository))->handle($data);
            ApiResponse::success($newWallet->toArray());
        } catch (\Throwable $e) {
            /** @var array<string, mixed> $exception */
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception["message"],
                $exception["status"],
                $exception["details"]
            );
        }
    }
}