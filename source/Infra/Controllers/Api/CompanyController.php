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
     * @param CompanyRegisterInputData $data
     * @return void
    */
    public function insert(CompanyRegisterInputData $data): void
    {
        try {
            if (empty($data->getUserId())) {
                $data->setUserId((int) $this->user->getId());
            }
            $newCompany = (new CompanyRegisterUsecase(new CompanyRepository()))->handle($data);
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