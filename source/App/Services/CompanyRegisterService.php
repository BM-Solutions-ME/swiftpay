<?php

declare(strict_types=1);

namespace Source\App\Services;

use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterInputData;
use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterOutputData;
use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterUsecase;
use Source\Domain\Repositories\CompanyRepositoryInterface;

final class CompanyRegisterService
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return CompanyRegisterOutputData
    */
    public function handle(array $data): CompanyRegisterOutputData
    {
        $input = new CompanyRegisterInputData(
            $data["user_id"],
            $data["public_name"],
            $data["legal_name"],
            $data["document"],
            $data["date_foundation"]
        );

        $register = new CompanyRegisterUsecase($this->repository);
        return $register->handle($input);
    }
}