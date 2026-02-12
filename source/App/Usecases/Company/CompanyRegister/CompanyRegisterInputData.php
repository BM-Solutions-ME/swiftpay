<?php

declare(strict_types=1);

namespace Source\App\Usecases\Company\CompanyRegister;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class CompanyRegisterInputData
{
    #[OA\Property(example: 1)]
    private ?int $userId;

    #[OA\Property(example: "SwiftPay")]
    private readonly string $publicName;

    #[OA\Property(example: "Test S/A")]
    private readonly string $legalName;

    #[OA\Property(example: "46.158.757/0001-09")]
    private readonly string $document;

    #[OA\Property(example:"01/01/2026")]
    private readonly ?string $dateFoundation;

    public function __construct(
        ?int $userId,
        string $publicName,
        string $legalName,
         string $document,
        ?string $dateFoundation
    ) {
        $this->userId = (!emptY($userId) ? $userId : null);
        $this->publicName = $publicName;
        $this->legalName = $legalName;
        $this->document = $document;
        $this->dateFoundation = (!empty($dateFoundation) ? $dateFoundation : null);
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getPublicName(): string
    {
        return $this->publicName;
    }

    public function getLegalName(): string
    {
        return $this->legalName;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getDateFoundation(): ?string
    {
        return $this->dateFoundation;
    }
}