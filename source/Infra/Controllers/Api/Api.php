<?php

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\User\GetUserById\GetUserByIdInputData;
use Source\App\Usecases\User\GetUserById\GetUserByIdUsecase;
use Source\Domain\Entities\User;
use Source\Presentation\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Repositories\UserRepository;

abstract class Api
{
    /** @var User $user */
    protected User $user;
    /** @var array<string, mixed> $headers */
    protected array $headers;
    /** @var array<string, mixed>|null $response */
    protected ?array $response;

    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = array_change_key_case(getallheaders(), CASE_LOWER);

        if (!$this->auth()) {
            exit;
        }
    }

    /**
     * @return boolean
    */
    protected function auth(): bool
    {
        if (empty($this->headers["token"])) {
            ApiResponse::error("Informe o token de acesso.", HttpStatusEnum::UNAUTHORIZED, []);
            return false;
        }

        $jwt = new JwtAdapter;

        if (!$jwt->tokenValidate($this->headers["token"])) {
            ApiResponse::error("Access token inválido.", HttpStatusEnum::UNAUTHORIZED, []);
            return false;
        }

        $token = $jwt->getPayload()->toArray();
        $userId = $token["sub"];
        $response = (new GetUserByIdUsecase(new UserRepository))->handle(new GetUserByIdInputData($userId));
        $user = new User();
        $user->hydrate($response->toArray());
        $this->user = $user;
        return true;
    }
}