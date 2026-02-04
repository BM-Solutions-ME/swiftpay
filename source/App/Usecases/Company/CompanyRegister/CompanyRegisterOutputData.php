<?php

declare(strict_types=1);

namespace Source\App\Usecases\Company\CompanyRegister;

use Source\Domain\Entities\Company;

final class CompanyRegisterOutputData
{
    private readonly int $id;
    private readonly int $userId;
    private readonly string $publicName;
    private readonly string $legalName;
    private readonly string $document;
    private readonly ?string $dateFoundation;
    private readonly string $createdAt;
    private readonly ?string $updatedAt;

    /**
     * @param Company $data
    */
    public function __construct(Company $data)
    {
        $this->id = $data->getId();
        $this->userId = $data->getUserId();
        $this->publicName = $data->getPublicName();
        $this->legalName = $data->getLegalName();
        $this->document = (string) $data->getDocument();
        $this->dateFoundation = $data->getDateFoundation();
        $this->createdAt = (!empty($data->getCreatedAt()) ? $data->getCreatedAt()->format("Y-m-d H:i:s") : '');
        $this->updatedAt = (!empty($data->getUpdatedAt()) ? $data->getUpdatedAt()->format("Y-m-d H:i:s") : '');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @return array<string, mixed>
    */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->userId,
            "public_name" => $this->publicName,
            "legal_name" => $this->legalName,
            "document" => $this->document,
            "date_foundation" => $this->dateFoundation,
            "created_at" => (!empty($this->createdAt) ? (new \DateTime($this->createdAt))->format('d/m/Y H\hi') : ''),
            "updated_at" => (!empty($this->updatedAt) ? (new \DateTime($this->updatedAt))->format('d/m/Y H\hi') : '')
        ];
    }
}