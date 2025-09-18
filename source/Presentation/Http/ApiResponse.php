<?php

declare(strict_types=1);

namespace Source\Presentation\Http;

use Source\Domain\Http\Enum\HttpStatusEnum;

/**
 *
 */
final class ApiResponse
{
    /**
     * @param array<string, mixed>|list<array<string, mixed>> $data
     * @param string $message
     * @param HttpStatusEnum $status
     * @return void
     */
    public static function success(
        array $data = [],
        string $message = 'OK',
        HttpStatusEnum $status = HttpStatusEnum::OK
    ): void {
        http_response_code($status->value);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // encerra a execução para não enviar mais nada
    }

    /**
     * @param string $message
     * @param HttpStatusEnum $status
     * @param array<int|string, mixed> $errors
     * @return void
     */
    public static function error(
        string $message,
        HttpStatusEnum $status = HttpStatusEnum::BAD_REQUEST,
        array $errors = []
    ): void {
        http_response_code($status->value);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}