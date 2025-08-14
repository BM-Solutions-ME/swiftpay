<?php

namespace Source\Infra\Controllers\Api;

class Pong
{
    public function index(): void
    {
        echo json_encode(["pong" => true], JSON_PRETTY_PRINT);
    }
}