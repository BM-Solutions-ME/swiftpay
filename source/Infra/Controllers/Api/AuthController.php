<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\AuthService;
use Source\App\Usecases\Auth\AuthOutputData;
use Source\Domain\Http\ApiResponse;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Repositories\AuthRepository;

class AuthController
{
    /** @param array<string, mixed> $data */
    public function index(array $data): void
    {
        try {
            /** @var AuthOutputData $authenticate */
            $authenticate = (new AuthService(new AuthRepository(), new JwtAdapter))->handle($data["email"], $data["password"]);
            ApiResponse::success(["data" => $authenticate->toArray()]);
        } catch (HttpException $e) {
            ApiResponse::error($e->getMessage(), $e->getStatus(), $e->getDetails());
        } catch (\Throwable $e) {
            ApiResponse::error(
                "Erro inesperado.",
                HttpStatusEnum::BAD_REQUEST,
                ['trace' => $e->getMessage()]
            );
        }
    }
}