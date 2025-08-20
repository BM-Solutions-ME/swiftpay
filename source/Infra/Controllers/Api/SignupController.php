<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\Domain\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\App\Services\SignupService;
use Source\App\Services\ValidateNewAccountService;
use Source\Domain\Http\HttpException;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\SignupRepository;
use Source\Infra\Repositories\UserRepository;

class SignupController
{
    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function register(array $data): void
    {
        try {
            $newUser = (new SignupService(new SignupRepository))->handle($data);
            ApiResponse::success($newUser->toArray());
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

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function validateNewAccount(array $data): void
    {
        try {
            (new ValidateNewAccountService(new UserRepository()))->handle($data);
            ApiResponse::success([]);
        } catch (\Throwable $e) {
            ApiResponse::error(
                "Erro inesperado.",
                HttpStatusEnum::BAD_REQUEST,
                ['trace' => $e->getMessage()]
            );
        }
    }
}