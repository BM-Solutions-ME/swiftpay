<?php

declare(strict_types=1);

namespace Source\App\Usecases\Company\CompanyRegister;

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
     * @param array<string, mixed> $data
    */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->userId = $data["user_id"];
        $this->publicName = $data["public_name"];
        $this->legalName = $data["legal_name"];
        $this->document = $data["document"];
        $this->dateFoundation = $data["date_foundation"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
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