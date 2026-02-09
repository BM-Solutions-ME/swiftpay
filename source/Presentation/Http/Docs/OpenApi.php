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
    url: "http://localhost/api",
    description: "Local server"
)]

/**
 * #[OA\Server(url: "http://localhost/api", description: "Local server")]
 * #[OA\Server(url: "https://test.swiftpay.com/api", description: "Staging")]
 * #[OA\Server(url: "https://swiftpay.com/api", description: "Production")]
*/
final class OpenApi {}