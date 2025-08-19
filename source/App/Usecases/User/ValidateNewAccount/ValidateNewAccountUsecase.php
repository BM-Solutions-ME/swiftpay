<?php

declare(strict_types=1);

namespace Source\App\Usecases\User\ValidateNewAccount;

use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Repositories\UserRepositoryInterface;

final class ValidateNewAccountUsecase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * @param ValidateNewAccountInputData $input
     * @return boolean
    */
    public function validate(ValidateNewAccountInputData $input): bool
    {
        if ($_ENV["SECRET_HASH_API"] !== $input->getSecret()) {
            throw new \Exception("Não foi possível completar esta operação.");
        }

        return $this->repository->validateNewAccount($input->getUserId());
    }
}