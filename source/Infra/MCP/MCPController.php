<?php

declare(strict_types=1);

namespace Source\Infra\MCP;

use Exception;
use Source\Infra\Adapters\JwtAdapter;

final class MCPController
{
    public function __construct(
        private MCPServer $server
    ) {}

    /**
     * @param array<string, mixed>|null $request
     */
    public function handle(?array $request = null): void
    {
        if ($request === null) {
            $request = json_decode(file_get_contents('php://input'), true);
        }

        $tool = $request['tool'] ?? '';
        $input = $request['input'] ?? [];

        $userId = $this->getAuthenticatedUserId(); // JWT

        $result = $this->server->execute($tool, $input, $userId);

        echo json_encode($result);
    }

    public function getAuthenticatedUserId(): int
    {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);

        if (empty($headers["token"])) {
            throw new Exception("Não foi possível obter a credencial de autenticação.");
        }

        $jwt = new JwtAdapter();

        if (!$jwt->tokenValidate($headers["token"])) {
            throw new Exception("Access token inválido.");
        }

        $token = $jwt->getPayload()->toArray();

        return (int) $token["sub"];
    }
}