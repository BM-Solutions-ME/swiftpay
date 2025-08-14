<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

final readonly class JwtPayload
{
    public function __construct(
        public int $iat,
        public int $sub,
        public int $exp
    )
    {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "iat" => $this->iat,
            "sub" => $this->sub,
            "exp" => $this->exp
        ];
    }
}