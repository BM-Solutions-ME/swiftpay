<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\Company;
use Source\Domain\Repositories\CompanyRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * @param Company $newCompany
     * @return array<string, mixed>
    */
    public function register(Company $newCompany): array
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var Company $companyRegistered */
        $companyRegistered = $repo->save($newCompany);
        return $companyRegistered->toArray();
    }
}