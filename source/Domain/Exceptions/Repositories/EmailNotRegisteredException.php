<?php

declare(strict_types=1);

namespace Source\Domain\Exceptions\Repositories;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;

/**
 *
 */
class EmailNotRegisteredException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            HttpStatusEnum::UNAUTHORIZED
        );
    }
}
