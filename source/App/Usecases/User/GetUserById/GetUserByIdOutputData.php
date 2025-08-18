<?php

declare(strict_types=1);

namespace Source\App\Usecases\User\GetUserById;

final class GetUserByIdOutputData
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $type;
    private string $document;
    private string $email;
    private int $level;
    private string $status;
    private string $createdAt;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
        $this->type = $data["type"];
        $this->document = $data["document"];
        $this->email = $data["email"];
        $this->level = $data["level"];
        $this->status = $data["status"];
        $this->createdAt = $data["created_at"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "type" => $this->type,
            "document" => $this->document,
            "email" => $this->email,
            "level" => $this->level,
            "status" => $this->status,
            "created_at" => (new \DateTime($this->createdAt))->format("d/m/Y H\hi")
        ];
    }
}