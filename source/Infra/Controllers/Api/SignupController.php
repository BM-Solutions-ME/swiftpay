<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Signup\SignupInputData;
use Source\App\Usecases\Signup\SignupUsecase;
use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountInputData;
use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountUsecase;
use Source\Presentation\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
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
            $input = new SignupInputData(
                $data["first_name"],
                 $data["last_name"],
                     $data["type"],
                 $data["document"],
                    $data["email"],
                 $data["password"]
            );

            $newUser = (new SignupUsecase(new SignupRepository))->handle($input);
            $responseApi = $newUser->toArray();
            $validateUserPayload = [
                "user_id" => $newUser->getId(),
                "user_status" => $newUser->getStatus(),
                "secret" => $_ENV["SECRET_HASH_API"]
            ];
            $responseApi["valdate_user_hash"] = base64_encode((string) json_encode($validateUserPayload));

            ApiResponse::success($responseApi);
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
    public function validateNewAccount(array $data): void
    {
        try {
            if (empty($data["userHash"])) {
                throw new \Exception("Link de validação inválido.");
            }
            $input = new ValidateNewAccountInputData($data["userHash"]);
            (new ValidateNewAccountUsecase(new UserRepository()))->validate($input);
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