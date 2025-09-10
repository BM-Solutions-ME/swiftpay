<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\Domain\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\App\Services\SignupService;
use Source\App\Services\ValidateNewAccountService;
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
            $responseApi = $newUser->toArray();
            $validateUserPayload = [
                "user_id" => $newUser->getId(),
                "user_status" => $newUser->getStatus(),
                "secret" => $_ENV["SECRET_HASH_API"]
            ];
            $responseApi["valdate_user_hash"] = base64_encode((string) json_encode($validateUserPayload));

            ApiResponse::success($responseApi);
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