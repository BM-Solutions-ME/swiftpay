<?php

declare(strict_types=1);

namespace Source\App\Usecases\Company\CompanyRegister;

final class CompanyRegisterInputData
{
    public function __construct(
        private ?int $userId,
        private readonly string $publicName,
        private readonly string $legalName,
        private readonly string $document,
        private readonly ?string $dateFoundation = null
    ) {}

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