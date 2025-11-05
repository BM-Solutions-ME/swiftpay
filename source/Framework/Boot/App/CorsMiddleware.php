<?php

declare(strict_types=1);

namespace Source\Framework\Boot\App;

final class CorsMiddleware
{
    /**
     * @return void
     */
    public static function handle(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: Content-Length, X-JSON');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization, action, email, password, token, credential');
        header('Access-Control-Max-Age: 86400');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit;
        }
    }
}