<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\MakeATransfer;

final class MakeTransferOutput
{
    private int $id;
    private string $payerName;
    private string $payeeName;
    private int $value;
    private \DateTimeInterface $createdAt;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->payerName = $data["payerName"] ?? '';
        $this->payeeName = $data["payeeName"] ?? '';
        $this->value = $data["value"] ?? '';
        $this->createdAt = $data["createdAt"] ?? '';
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

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): string
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
            "createdAt" => (!empty($this->createdAt) ? $this->createdAt->format("d/m/Y H\hi") : "")
        ];
    }
}