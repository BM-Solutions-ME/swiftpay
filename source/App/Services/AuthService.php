<?php

declare(strict_types=1);

namespace Source\App\Services;

use Source\App\Contracts\AuthToken;
use Source\App\Usecases\Auth\AuthInputData;
use Source\App\Usecases\Auth\AuthOutputData;
use Source\App\Usecases\Auth\AuthUsecase;
use Source\Domain\Repositories\AuthRepositoryInterface;
use Source\Domain\ValueObjects\Email;

readonly class AuthService
{
    public function __construct(
        private AuthRepositoryInterface $auth,
        private AuthToken      $jwt
    ) {}

    /**
     * @param string $email
     * @param string $password
     * @return AuthOutputData|null
    */
    public function handle(string $email, string $password): ?AuthOutputData
    {
        $email = new Email($email);
        $auth = new AuthUsecase($this->auth, $this->jwt);
        return $auth->handle(new AuthInputData($email, $password));
    }
}