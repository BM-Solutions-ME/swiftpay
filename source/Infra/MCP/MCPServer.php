<?php

declare(strict_types=1);

namespace Source\Infra\MCP;

final class MCPServer
{
    public function __construct(
        private ToolRegistry $registry
    ) {}

    public function execute(string $tool, array $input, int $userId): array
    {
        $toolInstance = $this->registry->get($tool);

        return $toolInstance->handle($input, $userId);
    }
}