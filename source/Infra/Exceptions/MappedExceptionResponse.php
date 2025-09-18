<?php

declare(strict_types=1);

namespace Source\Infra\Exceptions;

use Source\Domain\Http\Enum\HttpStatusEnum;

final class MappedExceptionResponse
{
    /**
     * @param string $message
     * @param HttpStatusEnum $status
     * @param array<string, mixed>|list<array<string, mixed>> $details
     */
    public function __construct(
        public readonly string $message,
        public readonly HttpStatusEnum $status,
        public readonly array $details
    ) {}
}