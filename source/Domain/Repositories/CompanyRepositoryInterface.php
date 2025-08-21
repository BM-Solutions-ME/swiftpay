<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\Company;

interface CompanyRepositoryInterface
{
    /**
     * @param Company $newCompany
     * @return array<string, mixed>
    */
    public function register(Company $newCompany): array;
}