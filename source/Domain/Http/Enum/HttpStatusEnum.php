<?php

declare(strict_types=1);

namespace Source\Domain\Http\Enum;

enum HttpStatusEnum: int
{
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case UNPROCESSABLE_ENTITY = 422;
    case INTERNAL_SERVER_ERROR = 500;

    public function message(): string
    {
        return match($this) {
            self::OK => 'Success',
            self::CREATED => 'Resource created',
            self::BAD_REQUEST => 'Bad request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not found',
            self::UNPROCESSABLE_ENTITY => 'Validation failed',
            self::INTERNAL_SERVER_ERROR => 'Server error',
        };
    }
}
