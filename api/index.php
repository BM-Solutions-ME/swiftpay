<?php

/**
 * BOOTSTRAP
 */

require __DIR__ . "/../vendor/autoload.php";

use Source\Framework\Boot\App\AppBootstrap;
use Source\Framework\Support\Router\Router;

AppBootstrap::init();

/**
 * API ROUTES
 * index
 */
$route = new Router(url(), ":");
$route->namespace("Source\Infra\Controllers\Api");

$route->group("/api");
$route->get("/ping", "Pong:index");

// Sign Up
$route->post("/user/register", "SignupController:register");
$route->get("/user/validate-new-account/{userHash}", "SignupController:validateNewAccount");

// Authentication
$route->post("/user/auth", "AuthController:index");

// company
$route->post("/company/insert", "CompanyController:insert");

// Wallet
$route->post("/wallet/all", "WalletController:all");
$route->get("/wallet/store/{wallet_id}", "WalletController:store");
$route->post("/wallet/balance","WalletController:balance");
$route->post("/wallet/create", "WalletController:create");

// Transfer


/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição API"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

AppBootstrap::shutdown();
