<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\AuthService;
use Source\App\Usecases\Auth\AuthOutputData;
use Source\Presentation\Http\ApiResponse;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\AuthRepository;

class AuthController
{
    /** @param array<string, mixed> $data */
    public function index(array $data): void
    {
        try {
            /** @var AuthOutputData $authenticate */
            $authenticate = (new AuthService(new AuthRepository(), new JwtAdapter))->handle($data["email"], $data["password"]);
            ApiResponse::success($authenticate->toArray());
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