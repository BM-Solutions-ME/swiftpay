<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Services\CompanyRegisterService;
use Source\Presentation\Http\ApiResponse;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\CompanyRepository;

final class CompanyController extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array<string, mixed> $data
     * @return void
    */
    public function insert(array $data): void
    {
        try {
            $data["user_id"] = (!empty($data["user_id"]) ? $data["user_id"] : $this->user->getId());
            $newCompany = (new CompanyRegisterService(new CompanyRepository))->handle($data);
            ApiResponse::success($newCompany->toArray());
        } catch (\Throwable $e) {
            $exception = MapExceptionToResponse::map($e);
            ApiResponse::error(
                $exception->message,
                $exception->status,
                $exception->details
            );
        }
    }
}