<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Notify\NotifyUsecase;
use Source\App\Usecases\Signup\SignupInputData;
use Source\App\Usecases\Signup\SignupUsecase;
use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountInputData;
use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountUsecase;
use Source\Infra\Adapters\EmailAdapter;
use Source\Presentation\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\SignupRepository;
use Source\Infra\Repositories\UserRepository;

use OpenApi\Attributes as OA;

class SignupController
{
    /**
     * @param SignupInputData $data
     * @return void
    */
    #[OA\Post(
        path: "/user/register",
        summary: "Create a new user account",
        tags: ["User"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: SignupInputData::class)
        ),
        responses: [
            new OA\Response(response: 201, description: "User created"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
    public function register(SignupInputData $data): void
    {
        try {
            $newUser = (new SignupUsecase(new SignupRepository))->handle($data);
            $responseApi = $newUser->toArray();
            $validateUserPayload = [
                "user_id" => $newUser->getId(),
                "user_status" => $newUser->getStatus(),
                "secret" => $_ENV["SECRET_HASH_API"]
            ];
            $validate_user_hash = base64_encode((string) json_encode($validateUserPayload));
            $linkValidateUser = CONF_URL_BASE . "/api/user/validate-new-account/{$validate_user_hash}";

            (new NotifyUsecase(new EmailAdapter([
                "subject" => "Confirme a validade do cadastro.",
                "body" => nl2br("<p>Olá {$newUser->getFirstName()}!\nClique no link abaixo para ativar sua conta.</p><br><a href='{$linkValidateUser}' target='_blank'>Confirmar</a>"),
                "recipient" => (string) $newUser->getEmail(),
                "recipientName" => $newUser->getFirstName()
            ])))->handle();

            ApiResponse::success($responseApi, "CREATED", HttpStatusEnum::CREATED);
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
     * @param ValidateNewAccountInputData $data
     * @return void
    */
    public function validateNewAccount(ValidateNewAccountInputData $data): void
    {
        try {
            (new ValidateNewAccountUsecase(new UserRepository()))->validate($data);
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