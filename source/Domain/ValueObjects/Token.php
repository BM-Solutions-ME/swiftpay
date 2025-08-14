<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

final readonly class Token
{
    public function __construct(
        public string $token,
        public int    $expiresAt
    ) {}

    /**
     * @return array<string, mixed>
    */
    public function toArray(): array
    {
        return [
            "token" => $this->token,
            "expires_at" => $this->expiresAt
        ];
    }
}