<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterInputData;
use Source\App\Usecases\Company\CompanyRegister\CompanyRegisterUsecase;
use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Presentation\Http\ApiResponse;
use Source\Infra\Exceptions\MapExceptionToResponse;
use Source\Infra\Repositories\CompanyRepository;

use OpenApi\Attributes as OA;

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
    #[OA\Post(
        path: "/company/insert",
        summary: "Create a new company",
        tags: ["Company"],
        security: [["token" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: CompanyRegisterInputData::class)
        ),
        responses: [
            new OA\Response(response: 201, description: "Company created"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
    public function insert(CompanyRegisterInputData $data): void
    {
        try {
            if (empty($data->getUserId())) {
                $data->setUserId((int) $this->user->getId());
            }
            $newCompany = (new CompanyRegisterUsecase(new CompanyRepository()))->handle($data);
            ApiResponse::success($newCompany->toArray(), "CREATED", HttpStatusEnum::CREATED);
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