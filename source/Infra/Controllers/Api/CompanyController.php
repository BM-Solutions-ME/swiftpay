<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterInputData;
use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterUsecase;
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
            $input = new CompanyRegisterInputData(
                $data["user_id"],
                $data["public_name"],
                $data["legal_name"],
                $data["document"],
                $data["date_foundation"]
            );
            $newCompany = (new CompanyRegisterUsecase(new CompanyRepository()))->handle($input);
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