<?php

declare(strict_types=1);

namespace Source\App\Usecases\Company\CompanyRegister;

use Source\Domain\Entities\Company;
use Source\Domain\Repositories\CompanyRepositoryInterface;

final class CompanyRegisterUsecase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository
    ) {}

    public function handle(CompanyRegisterInputData $input): CompanyRegisterOutputData
    {
        $newCompany = new Company;
        $newCompany->setUserId((int) $input->getUserId());
        $newCompany->setPublicName($input->getPublicName());
        $newCompany->setLegalName($input->getLegalName());
        $newCompany->setDocument($input->getDocument());
        $newCompany->setDateFoundation($input->getDateFoundation());

        $response = $this->repository->register($newCompany);
        return new CompanyRegisterOutputData($response);
    }
}