<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Domain\ValueObjects\Cnpj;

#[Table(name: "companies")]
final class Company implements PersistableEntityInterface
{
    #[Column(name: "id", type: "int", primaryKey: true)]
    private ?int $id;
    #[Column(name: "user_id", type: "int", required: true, requiredMessage: "Informe o usuário responsável pela empresa.")]
    private int $userId;
    #[Column(name: "public_name", type: "string", required: true, requiredMessage: "O nome público da empresa é obrigatório.")]
    private string $publicName;
    #[Column(name: "legal_name", type: "string", required: true, requiredMessage: "O nome legal da empresa é obrigatório.")]
    private string $legalName;
    #[Column(name: "document", type: "string", required: true, requiredMessage: "O cnpj é obrigatório.")]
    private Cnpj $document;
    #[Column(name: "date_foundation", type: "string")]
    private ?string $dateFoundation;
    #[Column(name: "created_at", type: "string", cast: "DateTime", protected: true)]
    private ?\DateTimeInterface $createdAt;
    #[Column(name: "updated_at", type: "string", cast: "DateTime", protected: true)]
    private ?\DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
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

    public function setPublicName(string $publicName): void
    {
        $this->publicName = $publicName;
    }

    public function getLegalName(): string
    {
        return $this->legalName;
    }

    public function setLegalName(string $legalName): void
    {
        $this->legalName = $legalName;
    }

    public function getDocument(): Cnpj
    {
        return $this->document;
    }

    public function setDocument(string $document): void
    {
        $this->document = new Cnpj($document);
    }

    public function getDateFoundation(): ?string
    {
        return $this->dateFoundation;
    }

    public function setDateFoundation(?string $dateFoundation): void
    {
        $this->dateFoundation = $dateFoundation;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
            "document" => (string) $this->document,
            "date_foundation" => $this->dateFoundation,
            "created_at" => (!empty($this->createdAt) ? $this->createdAt->format("Y-m-d H:i:s") : ''),
            "updated_at" => (!empty($this->updatedAt) ? $this->updatedAt->format("Y-m-d H:i:s") : '')
        ];
    }
}