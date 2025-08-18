<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Enum\UserTypeEnum;
use Source\Domain\ValueObjects\Token;

final class AuthOutputData
{
    private int $id;
    private string $type;
    private string $firstName;
    private string $lastName;
    private string $document;
    private string $email;
    private string $status;
    private string $createdAt;
    private Token $authenticate;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->type = (UserTypeEnum::from($data["type"]))->value;
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
        $this->document = $data["document"];
        $this->email = $data["email"];
        $this->status = (UserStatusEnum::from($data["status"]))->value;
        $this->createdAt = $data["created_at"];
        $this->authenticate = $data["authenticate"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getAuthenticate(): Token
    {
        return $this->authenticate;
    }

    /**
     * @return array<string, mixed>
    */
    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "type" => $this->type,
            "first_name" => $this->getFirstName(),
            "last_name" => $this->getLastName(),
            "document" => $this->document,
            "email" => $this->getEmail(),
            "status" => $this->getStatus(),
            "created_at" => $this->getCreatedAt(),
            "authenticate" => $this->getAuthenticate()->toArray()
        ];
    }
}