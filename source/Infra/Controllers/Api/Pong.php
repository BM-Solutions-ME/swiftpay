<?php

namespace Source\Infra\Controllers\Api;

use Source\Presentation\Http\ApiResponse;

class Pong
{
    public function index(): void
    {
        ApiResponse::success(["pong" => true]);
    }
}