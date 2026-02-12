<?php

namespace Source\Infra\Controllers\Api;

use Source\Presentation\Http\ApiResponse;

use OpenApi\Attributes as OA;

class Pong
{
    #[OA\Get(
        path: "/ping",
        summary: "Test Api",
        tags: ["Test"],
        responses: [
            new OA\Response(response: 200, description: "Api ok"),
            new OA\Response(response: 400, description: "Validation error")
        ]
    )]
    public function index(): void
    {
        ApiResponse::success(["pong" => true]);
    }
}