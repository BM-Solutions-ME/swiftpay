<?php

declare(strict_types=1);

namespace Source\Infra\MCP;

use Exception;
use Source\Infra\MCP\Tools\GetWalletBalanceTool;

final class ToolRegistry
{
    public function get(string $name): object
    {
        return match($name) {
            "get_wallet_balance" => new GetWalletBalanceTool(),
            default => throw new Exception("Tool not found")
        };
    }
}