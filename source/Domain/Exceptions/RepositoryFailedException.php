<?php

declare(strict_types=1);

namespace Source\Domain\Exceptions;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;

final class RepositoryFailedException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            HttpStatusEnum::INTERNAL_SERVER_ERROR
        );
    }
}
