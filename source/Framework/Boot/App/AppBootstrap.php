<?php

declare(strict_types=1);

namespace Source\Framework\Boot\App;

final class AppBootstrap
{
    /**
     * @return void
     */
    public static function init(): void
    {
        ob_start();

        // CORS
        CorsMiddleware::handle();

        // Error handler
        ErrorHandler::register();
    }

    /**
     * @return void
     */
    public static function shutdown(): void
    {
        ob_end_flush();
    }
}