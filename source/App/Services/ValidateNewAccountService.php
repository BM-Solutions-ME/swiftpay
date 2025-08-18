<?php

declare(strict_types=1);

namespace Source\App\Services;

use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountInputData;
use Source\App\Usecases\User\ValidateNewAccount\ValidateNewAccountUsecase;
use Source\Domain\Repositories\UserRepositoryInterface;

final class ValidateNewAccountService
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function handle(array $data): void
    {
        if (empty($data["userHash"])) {
            throw new \Exception("Link de validação inválido.");
        }

        (new ValidateNewAccountUsecase($this->repository))->validate(new ValidateNewAccountInputData($data["userHash"]));
    }
}