<?php

namespace Source\Presentation\Http\Docs;

use OpenApi\Attributes as OA;


// Gerar doc - vendor/bin/openapi source -o public/openapi.json 
#[OA\Info(
    title: "Swiftpay API",
    version: "1.0.0",
    description: "API de transferências, carteiras e usuários"
)]
#[OA\Server(
    url: "http://localhost",
    description: "Local server"
)]
final class OpenApi {}