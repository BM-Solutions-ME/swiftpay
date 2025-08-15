<?php

namespace Source\App\Services;

use Source\App\Usecases\Signup\SignupInputData;
use Source\App\Usecases\Signup\SignupOutputData;
use Source\App\Usecases\Signup\SignupUsecase;
use Source\Domain\Repositories\SignupRepositoryInterface;

class SignupService
{
    public function __construct(
        private readonly SignupRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return SignupOutputData
    */
    public function handle(array $data): SignupOutputData
    {
        $input = new SignupInputData(
            $data["first_name"],
            $data["last_name"],
            $data["type"],
            $data["document"],
            $data["email"],
            $data["password"],
        );

        $register = new SignupUsecase($this->repository);
        return $register->handle($input);
    }
}