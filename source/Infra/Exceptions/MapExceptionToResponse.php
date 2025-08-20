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
     * @return array<string, mixed>
    */
    public static function map(\Throwable $e): array
    {
        return match (true) {
            $e instanceof HttpException => [
                'message' => $e->getMessage(),
                'status'  => $e->getStatus(),
                'details' => $e->getDetails()
            ],
            $e instanceof \PDOException => [
                'message' => MapDatabaseException::map($e),
                'status' => HttpStatusEnum::CONFLICT->value,
                'details' => $e->getTrace()
            ],
            default => [
                'message' => 'Erro inesperado.',
                'status'  => HttpStatusEnum::BAD_REQUEST,
                'details' => ['trace' => $e->getMessage()]
            ]
        };
    }
}