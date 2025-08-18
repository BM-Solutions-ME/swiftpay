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
        $response = $this->repository->execute($input->getEmail(), $input->getPassword());
        $response["created_at"] = (new DateTime($response["created_at"]))->format("d/m/Y H\hi");
        $response["authenticate"] = $this->authToken->tokenGenerate($response["id"]);
        return new AuthOutputData($response);
    }
}