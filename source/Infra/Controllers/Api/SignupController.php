<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\SignupService;
use Source\Framework\Support\CallbackHandler;
use Source\Infra\Repositories\SignupRepository;

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
}