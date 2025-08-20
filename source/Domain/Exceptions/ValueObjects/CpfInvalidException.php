<?php

declare(strict_types=1);

namespace Source\Domain\Exceptions\ValueObjects;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;

class CpfInvalidException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            HttpStatusEnum::BAD_REQUEST
        );
    }
}
