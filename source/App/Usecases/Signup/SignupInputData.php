<?php

declare(strict_types=1);

namespace Source\App\Usecases\Signup;

use OpenApi\Attributes as OA;
use Source\Domain\Enum\UserTypeEnum;

#[OA\Schema]
final class SignupInputData
{
    #[OA\Property(example: "Hector")]
    private string $firstName;
    #[OA\Property(example:"Bonilla")]
    private string $lastName;
    #[OA\Property(example: UserTypeEnum::Person)]
    private UserTypeEnum $type;
    #[OA\Property(example:"737.557.700-59")]
    private string $document;
    #[OA\Property(example:"hector.bonilla@gmail.com")]
    private string $email;
    #[OA\Property(example:"12345678")]
    private string $password;
    #[OA\Property(example: 1)]
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