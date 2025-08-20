<?php

declare(strict_types=1);

namespace Source\Infra\Database\Exceptions;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;

final class IntegrityConstraintViolationException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            HttpStatusEnum::CONFLICT // 409 é o ideal para conflitos de integridade
        );
    }
}