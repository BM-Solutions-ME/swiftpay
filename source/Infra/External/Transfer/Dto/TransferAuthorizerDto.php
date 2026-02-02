<?php

declare(strict_types=1);

namespace Source\Infra\External\Transfer\Dto;

final class TransferAuthorizerDto
{
    /**
     * @param string $status
     * @param array<string, mixed> $data
    */
    public function __construct(
        private string $status,
        private array $data
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return self
    */
    public static function fromArray(array $data): self
    {
        return new self(
            $data["status"] ?? '', 
            $data["data"] ?? []
        );
    }

    /**
     * @return boolean
    */
    public function isAthorized(): bool
    {
        return $this->status === 'success';
    }

    /**
     * @return array<string, mixed>
    */
    public function getData(): array
    {
        return $this->data;
    }
}