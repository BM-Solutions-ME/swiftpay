<?php

declare(strict_types=1);

namespace Source\App\Usecases\User\GetUserById;

use Source\Domain\Repositories\UserRepositoryInterface;

final class GetUserByIdUsecase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * @param GetUserByIdInputData $input
     * @return GetUserByIdOutputData
    */
    public function handle(GetUserByIdInputData $input): GetUserByIdOutputData
    {
        $user = $this->repository->getUserById($input->getUserId());
        return (new GetUserByIdOutputData($user->toArray()));
    }
}