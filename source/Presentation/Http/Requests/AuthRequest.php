<?php

declare(strict_types=1);

namespace Source\Presentation\Http\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class AuthRequest
{
    #[OA\Property(example:"hector.bonilla@gmail.com")]
    public readonly string $email;

    #[OA\Property(example:"12345678")]
    public readonly string $password;

    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
    }
}