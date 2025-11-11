<?php

declare(strict_types=1);

namespace unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\Transfer;
use Source\Domain\Enum\TransferStatusEnum;

final class TransferTest extends TestCase
{
    public function testShouldSetAndGetProperties(): void
    {
        $transfer = new Transfer();

        $transfer->setId(1);
        $transfer->setWalletSender(10);
        $transfer->setWalletReceiver(20);
        $transfer->setAmount(1000);
        $transfer->setStatus(TransferStatusEnum::PENDING);

        $this->assertSame(1, $transfer->getId());
        $this->assertSame(10, $transfer->getWalletSender());
        $this->assertSame(20, $transfer->getWalletReceiver());
        $this->assertSame(1000, $transfer->getAmount());
        $this->assertSame(TransferStatusEnum::PENDING, $transfer->getStatus());
    }

    public function testShouldHandleNullableDates(): void
    {
        $transfer = new Transfer();

        $transfer->setCreatedAt(null);
        $transfer->setUpdatedAt(null);

        $this->assertNull($transfer->getCreatedAt());
        $this->assertNull($transfer->getUpdatedAt());
    }

    public function testShouldConvertToArrayCorrectly(): void
    {
        $transfer = new Transfer();

        $transfer->setId(99);
        $transfer->setWalletSender(10);
        $transfer->setWalletReceiver(20);
        $transfer->setAmount(500);
        $transfer->setStatus(TransferStatusEnum::COMPLETED);

        $createdAt = new \DateTimeImmutable('2025-11-07 14:30:00');
        $updatedAt = new \DateTimeImmutable('2025-11-07 15:00:00');

        $transfer->setCreatedAt($createdAt);
        $transfer->setUpdatedAt($updatedAt);

        $array = $transfer->toArray();

        $this->assertIsArray($array);
        $this->assertSame(99, $array['id']);
        $this->assertSame(10, $array['wallet_sender']);
        $this->assertSame(20, $array['wallet_receiver']);
        $this->assertSame(500, $array['amount']);
        $this->assertSame('completed', $array['status']);
        $this->assertSame('2025-11-07 14:30:00', $array['created_at']);
        $this->assertSame('2025-11-07 15:00:00', $array['updated_at']);
    }

    public function testShouldReturnEmptyStringsForNullDatesInArray(): void
    {
        $transfer = new Transfer();

        $transfer->setId(5);
        $transfer->setWalletSender(10);
        $transfer->setWalletReceiver(20);
        $transfer->setAmount(100);
        $transfer->setStatus(TransferStatusEnum::FAILED);
        $transfer->setCreatedAt(null);
        $transfer->setUpdatedAt(null);

        $array = $transfer->toArray();

        $this->assertSame('', $array['created_at']);
        $this->assertSame('', $array['updated_at']);
    }
}