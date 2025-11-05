<?php

declare(strict_types=1);

namespace Source\Framework\Boot\App;

use Source\Framework\Support\Monolog;

final class ErrorHandler
{
    /**
     * @return void
     */
    public static function register(): void
    {
        register_shutdown_function([self::class, 'handleFatal']);
    }

    /**
     * @return void
     */
    public static function handleFatal(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [
                E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING,
                E_COMPILE_ERROR, E_COMPILE_WARNING, E_RECOVERABLE_ERROR
            ], true)) {
            $log = new Monolog("api", sprintf(
                "%s [%s linha: %d]",
                $error['message'],
                $error['file'],
                $error['line']
            ));

            $log->emergency();

            // Opcional: responder JSON padronizado
            if (php_sapi_name() !== 'cli') {
                http_response_code(500);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'errors' => [
                        'type' => 'fatal_error',
                        'message' => 'Ocorreu um erro interno no servidor'
                    ]
                ], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}