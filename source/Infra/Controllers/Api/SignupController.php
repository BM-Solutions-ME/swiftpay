<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\App\Services\SignupService;
use Source\App\Services\ValidateNewAccountService;
use Source\Framework\Support\CallbackHandler;
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
            (new CallbackHandler())->output(code: 201, response: ["data" => $newUser->toArray()]);
        } catch (\Throwable $e) {
            (new CallbackHandler())->set(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->output();
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
            (new CallbackHandler())->output(code: HttpStatusEnum::OK->value, response: ["success" => true]);
        } catch (\Throwable $e) {
            (new CallbackHandler())->set(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->output();
        }
    }
}