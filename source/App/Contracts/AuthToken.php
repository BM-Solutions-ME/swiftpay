<?php

declare(strict_types=1);

namespace Source\App\Contracts;

use Source\Domain\ValueObjects\JwtPayload;
use Source\Domain\ValueObjects\Token;

interface AuthToken
{
    public function tokenGenerate(int $userId): Token;
    public function tokenValidate(string $token): bool;

    public function getPayload(): JwtPayload;
}
