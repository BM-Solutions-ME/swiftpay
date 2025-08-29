<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Contracts\PersistableEntityInterface;

#[Table(name: "wallet")]
final class Wallet implements PersistableEntityInterface
{
    #[Column(name: "id", type: "int", primaryKey: true)]
    private ?int $id;
    #[Column(name: "user_id", type: "int", required: true, requiredMessage: "Informe o usuário responsável pela carteira.")]
    private int $userId;
    #[Column(name: "company_id", type: "int")]
    private ?int $companyId;
    #[Column(name: "balance", type: "int")]
    private int $balance;
    #[Column(name: "created_at", type: "string", protected: true)]
    private ?\DateTimeInterface $createdAt;
    #[Column(name: "updated_at", type: "string", protected: true)]
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

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
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
            "company_id" => $this->companyId,
            "balance" => $this->balance,
            "created_at" => (!empty($this->createdAt) ? $this->createdAt->format("Y-m-d H:i:s") : ''),
            "updated_at" => (!empty($this->updatedAt) ? $this->updatedAt->format("Y-m-d H:i:s") : '')
        ];
    }
}