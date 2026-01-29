<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer\CreateTransfer;

final class CreateTransferOutput
{
    private int $id;
    private int $walletReceiverId;
    private int $walletSenderId;

    private string $status;
    private int $value;
    private \DateTimeInterface $createdAt;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->walletReceiverId = (int) $data["walletReceiverId"];
        $this->walletSenderId = (int) $data["walletSenderId"];
        $this->status = $data["status"];
        $this->value = $data["value"];
        $this->createdAt = $data["createdAt"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWalletReceiverId(): int
    {
        return $this->walletReceiverId;
    }

    public function getWalletSenderId(): int
    {
        return $this->walletSenderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getValue(): int
    {
        return $this->value;
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
            "walletSenderId" => $this->walletSenderId,
            "walletReceiverId" => $this->walletReceiverId,
            "status" => $this->status,
            "value" => $this->value,
            "createdAt" => $this->createdAt
        ];
    }
}