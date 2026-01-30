<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Auth\AuthInputData;
use Source\App\Usecases\Auth\AuthUsecase;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;
use Source\Presentation\Http\ApiResponse;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\AuthRepository;
use Source\Presentation\Http\Requests\AuthRequest;

class AuthController
{
    /** @param AuthRequest $data */
    public function index(AuthRequest $data): void
    {
        try {
            $input = new AuthInputData(
                new Email($data->email), 
                $data->password
            );
            $authenticate = (new AuthUsecase(
                new AuthRepository, 
                 new JwtAdapter))
                 ->handle($input);
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