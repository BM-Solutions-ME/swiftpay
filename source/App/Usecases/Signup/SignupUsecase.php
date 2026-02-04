<?php

declare(strict_types=1);

namespace Source\App\Usecases\Signup;

use Source\Domain\Entities\User;
use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Repositories\SignupRepositoryInterface;

final class SignupUsecase
{
    public function __construct(
        private readonly SignupRepositoryInterface $repository
    ) {}

    public function handle(SignupInputData $input): SignupOutputData
    {
        $newUser = new User();
        $newUser->setType($input->getType());
        $newUser->setFirstName($input->getFirstName());
        $newUser->setLastName($input->getLastName());
        $newUser->setDocument($input->getDocument());
        $newUser->setEmail($input->getEmail());
        $newUser->setPassword($input->getPassword());
        $newUser->setLevel(1);
        $newUser->setStatus(UserStatusEnum::Registered);

        /** @var User $response */
        $response = $this->repository->register($newUser);
        return new SignupOutputData($response);
    }
}