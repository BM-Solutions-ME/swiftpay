<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Domain\Enum\TransferStatusEnum;

#[Table(name: "Transfer")]
final class Transfer implements PersistableEntityInterface
{
    #[Column(name: "id", type: "int", primaryKey: true)]
    private ?int $id;
    #[Column(name: "wallet_sender", type: "int", required: true, requiredMessage: "Informe a carteira de onde o saldo será transferido.")]
    private int $walletSender;
    #[Column(name: "wallet_receiver", type: "int", required: true, requiredMessage: "Informa a carteira que receberá a transferência.")]
    private int $walletReceiver;
    #[Column(name: "amount", type: "int", required: true, requiredMessage: "Informe o valor que deve ser transferido.")]
    private int $amount;
    #[Column(name: "status", type: "string", cast: TransferStatusEnum::class, required: true)]
    private TransferStatusEnum $status;
    #[Column(name: "created_at", type: "string", cast: "DateTime", protected: true)]
    private ?\DateTimeInterface $createdAt;
    #[Column(name: "updated_at", type: "string", cast: "DateTime", protected: true)]
    private ?\DateTimeInterface $updatedAt;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getWalletSender(): int
    {
        return $this->walletSender;
    }

    public function setWalletSender(int $walletSender): void
    {
        $this->walletSender = $walletSender;
    }

    public function getWalletReceiver(): int
    {
        return $this->walletReceiver;
    }

    public function setWalletReceiver(int $walletReceiver): void
    {
        $this->walletReceiver = $walletReceiver;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): TransferStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TransferStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "wallet_sender" => $this->walletSender,
            "wallet_receiver" => $this->walletReceiver,
            "amount" => $this->amount,
            "status" => $this->status->value,
            "created_at" => (!empty($this->createdAt) ? $this->createdAt->format('Y-m-d H:i:s') : ''),
            "updated_at" => (!empty($this->updatedAt) ? $this->updatedAt->format('Y-m-d H:i:s') : '')
        ];
    }
}