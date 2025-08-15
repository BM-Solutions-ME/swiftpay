<?php

declare(strict_types=1);

namespace Source\App\Usecases\Signup;

use Source\Domain\Enum\UserTypeEnum;

final class SignupInputData
{
    private string $firstName;
    private string $lastName;
    private UserTypeEnum $type;
    private string $document;
    private string $email;
    private string $password;
    private int $level;

    public function __construct(
        string $firstName,
        string $lastName,
        string $type,
        string $document,
        string $email,
        string $password,
        int $level = 1
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->type = UserTypeEnum::from($type);
        $this->document = $document;
        $this->email = $email;
        $this->password = $password;
        $this->level = $level;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getType(): UserTypeEnum
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}