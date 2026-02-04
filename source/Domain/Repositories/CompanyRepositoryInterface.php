<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\Company;

interface CompanyRepositoryInterface
{
    /**
     * @param Company $newCompany
     * @return Company
    */
    public function register(Company $newCompany): Company;
}