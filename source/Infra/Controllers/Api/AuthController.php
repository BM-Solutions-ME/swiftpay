<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\AuthService;
use Source\App\Usecases\Auth\AuthOutputData;
use Source\Framework\Support\CallbackHandler;
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
            (new CallbackHandler)->output(response: ["data" => $authenticate->toArray()]);
        } catch (\Throwable $e) {
            (new CallbackHandler)->set(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->output();
            exit();
        }
    }
}