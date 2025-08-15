<?php

namespace Source\Infra\Controllers\Api;

use Source\Framework\Support\CallbackHandler;

class Pong
{
    public function index(): void
    {
        (new CallbackHandler())->output(code: 200, response: ["pong" => true]);
    }
}