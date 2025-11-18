<?php

declare(strict_types=1);

namespace unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\Wallet;

final class WalletTest extends TestCase
{
    public function testShouldSetAndGetProperties(): void
    {
        $wallet = new Wallet();

        $wallet->setId(10);
        $wallet->setUserId(1);
        $wallet->setCompanyId(2);
        $wallet->setTitle("Minha Carteira");
        $wallet->setBalance(500);

        $this->assertSame(10, $wallet->getId());
        $this->assertSame(1, $wallet->getUserId());
        $this->assertSame(2, $wallet->getCompanyId());
        $this->assertSame("Minha Carteira", $wallet->getTitle());
        $this->assertSame(500, $wallet->getBalance());
    }

    public function testShouldHandleNullableFields(): void
    {
        $wallet = new Wallet();

        $wallet->setId(null);
        $wallet->setCompanyId(null);
        $wallet->setCreatedAt(null);
        $wallet->setUpdatedAt(null);

        $this->assertNull($wallet->getId());
        $this->assertNull($wallet->getCompanyId());
        $this->assertNull($wallet->getCreatedAt());
        $this->assertNull($wallet->getUpdatedAt());
    }

    public function testShouldConvertToArrayCorrectly(): void
    {
        $wallet = new Wallet();

        $wallet->setId(10);
        $wallet->setUserId(1);
        $wallet->setCompanyId(2);
        $wallet->setTitle("Principal");
        $wallet->setBalance(999);

        $createdAt = new \DateTimeImmutable("2025-11-07 14:00:00");
        $updatedAt = new \DateTimeImmutable("2025-11-07 15:00:00");

        $wallet->setCreatedAt($createdAt);
        $wallet->setUpdatedAt($updatedAt);

        $array = $wallet->toArray();

        $this->assertIsArray($array);
        $this->assertSame(10, $array["id"]);
        $this->assertSame(1, $array["user_id"]);
        $this->assertSame(2, $array["company_id"]);
        $this->assertSame("Principal", $array["title"]);
        $this->assertSame(999, $array["balance"]);
        $this->assertSame("2025-11-07 14:00:00", $array["created_at"]);
        $this->assertSame("2025-11-07 15:00:00", $array["updated_at"]);
    }

    public function testShouldReturnEmptyStringsForNullDatesInArray(): void
    {
        $wallet = new Wallet();
        $wallet->setId(10);
        $wallet->setUserId(1);
        $wallet->setCompanyId(null);
        $wallet->setTitle("Sem Data");
        $wallet->setBalance(0);
        $wallet->setCreatedAt(null);
        $wallet->setUpdatedAt(null);

        $array = $wallet->toArray();

        $this->assertSame('', $array["created_at"]);
        $this->assertSame('', $array["updated_at"]);
    }
}