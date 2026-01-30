<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

use DateTime;
use Exception;
use Source\App\Contracts\AuthToken;
use Source\Domain\Repositories\AuthRepositoryInterface;

final class AuthUsecase
{
    private AuthRepositoryInterface $repository;
    private AuthToken $authToken;

    public function __construct(
        AuthRepositoryInterface $repository,
        AuthToken $authToken
    ) {
        $this->repository = $repository;
        $this->authToken = $authToken;
    }

    /**
     * @throws Exception
     */
    public function handle(AuthInputData $input): AuthOutputData
    {
        $user = $this->repository->execute($input->getEmail(), $input->getPassword());
        $response = $user->toArray();
        $response["created_at"] = (!empty($user->getCreatedAt()) ? $user->getCreatedAt()->format("d/m/Y H\hi") : '');
        $response["authenticate"] = $this->authToken->tokenGenerate((int) $user->getId());
        return new AuthOutputData($response);
    }
}