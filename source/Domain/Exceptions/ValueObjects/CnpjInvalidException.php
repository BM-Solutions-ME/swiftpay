<?php

declare(strict_types=1);

namespace Source\Domain\Exceptions\ValueObjects;

use Exception;
use Throwable;

class CnpjInvalidException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
