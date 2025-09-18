<?php

declare(strict_types=1);

namespace Source\Infra\Exceptions;

use Source\Domain\Http\Enum\HttpStatusEnum;
use Source\Domain\Http\HttpException;
use Source\Infra\Database\Exceptions\MapDatabaseException;

final class MapExceptionToResponse
{
    /**
     * @param \Throwable $e
     * @return MappedExceptionResponse
    */
    public static function map(\Throwable $e): MappedExceptionResponse
    {
        return match (true) {
            $e instanceof HttpException => new MappedExceptionResponse(
                $e->getMessage(),
                $e->getStatus(),
                $e->getDetails()
            ),
            $e instanceof \PDOException => new MappedExceptionResponse(
                MapDatabaseException::map($e),
                HttpStatusEnum::CONFLICT,
                $e->getTrace()
            ),
            default => new MappedExceptionResponse(
                'Erro inesperado.',
                HttpStatusEnum::BAD_REQUEST,
                ['trace' => $e->getMessage()]
            )
        };
    }
}