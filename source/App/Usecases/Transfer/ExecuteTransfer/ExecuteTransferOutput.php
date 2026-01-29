<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\ExecuteTransfer;

use Source\Domain\Enum\TransferStatusEnum;

final class ExecuteTransferOutput
{
    private int $id;
    private string $payerName;
    private string $payeeName;
    private int $value;

    private string $status;
    private \DateTimeInterface $createdAt;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->payerName = $data["payerName"] ?? '';
        $this->payeeName = $data["payeeName"] ?? '';
        $this->value = $data["value"];
        $this->status = $data["status"] ?? '';
        $this->createdAt = $data["createdAt"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPayerName(): string
    {
        return $this->payerName;
    }

    public function getPayeeName(): string
    {
        return $this->payeeName;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "payerName" => $this->payerName,
            "payeeName" => $this->payeeName,
            "value" => $this->value,
            "status" => $this->status,
            "createdAt" => $this->createdAt->format("d/m/Y H\hi")
        ];
    }
}