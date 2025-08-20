<?php

namespace Source\Infra\Controllers\Api;

use Source\Domain\Http\ApiResponse;

class Pong
{
    public function index(): void
    {
        ApiResponse::success(["pong" => true]);
    }
}