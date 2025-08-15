<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTimeInterface;
use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Enum\UserTypeEnum;
use Source\Domain\Exceptions\ValueObjects\CpfInvalidException;
use Source\Domain\Exceptions\ValueObjects\EmailInvalidException;
use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;
use Source\Domain\Traits\HydrateTrait;
use Source\Domain\ValueObjects\Cpf;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

#[Table(name: "users")]
final class User implements PersistableEntityInterface
{
    use HydrateTrait;

    #[Column(name: "id", type: "int", primaryKey: true)]
    private ?int $id;
    #[Column(name: "type", type: "string", cast: UserTypeEnum::class, required: true, requiredMessage: "O tipo de cadastro do usuário é obrigatório.")]
    private UserTypeEnum $type;
    #[Column(name: "first_name", type: "string", required: true, requiredMessage: "Nome é obrigatório!")]
    private string $firstName;
    #[Column(name: "last_name", type: "string", required: true, requiredMessage: "Sobrenome é obrigatório!")]
    private string $lastName;
    #[Column(name: "document", type: "string", required: true, requiredMessage: "O Cpf é obrigatório.")]
    private Cpf $document;
    #[Column(name: "email", type: "string", required: true, requiredMessage: "O e-mail é obrigatório.")]
    private Email $email;
    #[Column(name: "password", type: "string")]
    private Password $password;
    #[Column(name: "level", type: "int", required: true)]
    private int $level;
    #[Column(name: "status", type: "string", cast: UserStatusEnum::class, required: true)]
    private UserStatusEnum $status;
    #[Column(name: "created_at", type: "string", cast: "DateTime", protected: true)]
    private ?DateTimeInterface $createdAt;
    #[Column(name: "updated_at", type: "string", cast: "DateTime", protected: true)]
    private ?DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getType(): UserTypeEnum
    {
        return $this->type;
    }

    public function setType(UserTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getDocument(): Cpf
    {
        return $this->document;
    }

    /**
     * @throws CpfInvalidException
     */
    public function setDocument(string $document): void
    {
        $this->document = new Cpf($document);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @throws EmailInvalidException
     */
    public function setEmail(string $email): void
    {
        $this->email = new Email($email);
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @throws PasswordInvalidDigitsNumber
     */
    public function setPassword(string $password): void
    {
        $this->password = new Password($password);
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getStatus(): UserStatusEnum
    {
        return $this->status;
    }

    public function setStatus(UserStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
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
            "type" => $this->type->value,
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "document" => (string) $this->document,
            "email" => (string) $this->email,
            "password" => (string) $this->password,
            "level" => $this->level,
            "status" => $this->status->value,
            "created_at" => (!empty($this->createdAt) ? $this->createdAt->format("Y-m-d H:i:s") : ''),
            "updated_at" => (!empty($this->updatedAt) ? $this->updatedAt->format("Y-m-d H:i:s") : '')
        ];
    }
}