<?php

declare(strict_types=1);

namespace unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\Company;
use Source\Domain\ValueObjects\Cnpj;

final class CompanyTest extends TestCase
{
    public function testShouldSetAndGetProperties(): void
    {
        $company = new Company();

        $company->setId(1);
        $company->setUserId(10);
        $company->setPublicName("Minha Empresa");
        $company->setLegalName("Minha Empresa LTDA");
        $company->setDocument("08.860.488/0001-39");
        $company->setDateFoundation("2020-05-15");

        $this->assertSame(1, $company->getId());
        $this->assertSame(10, $company->getUserId());
        $this->assertSame("Minha Empresa", $company->getPublicName());
        $this->assertSame("Minha Empresa LTDA", $company->getLegalName());

        $this->assertInstanceOf(Cnpj::class, $company->getDocument());
        $this->assertSame("08.860.488/0001-39", (string) $company->getDocument());
        $this->assertSame("2020-05-15", $company->getDateFoundation());
    }

    public function testShouldHandleNullableFields(): void
    {
        $company = new Company();

        $company->setId(null);
        $company->setDateFoundation(null);
        $company->setCreatedAt(null);
        $company->setUpdatedAt(null);

        $this->assertNull($company->getId());
        $this->assertNull($company->getDateFoundation());
        $this->assertNull($company->getCreatedAt());
        $this->assertNull($company->getUpdatedAt());
    }

    public function testShouldConvertToArrayCorrectly(): void
    {
        $company = new Company();

        $company->setId(7);
        $company->setUserId(2);
        $company->setPublicName("Tech Corp");
        $company->setLegalName("Tech Corp Soluções Digitais LTDA");
        $company->setDocument("08.860.488/0001-39");
        $company->setDateFoundation("2015-10-10");

        $createdAt = new \DateTimeImmutable("2025-11-07 10:00:00");
        $updatedAt = new \DateTimeImmutable("2025-11-07 11:00:00");

        $company->setCreatedAt($createdAt);
        $company->setUpdatedAt($updatedAt);

        $array = $company->toArray();

        $this->assertIsArray($array);
        $this->assertSame(7, $array["id"]);
        $this->assertSame(2, $array["user_id"]);
        $this->assertSame("Tech Corp", $array["public_name"]);
        $this->assertSame("Tech Corp Soluções Digitais LTDA", $array["legal_name"]);
        $this->assertSame("08.860.488/0001-39", $array["document"]);
        $this->assertSame("2015-10-10", $array["date_foundation"]);
        $this->assertSame("2025-11-07 10:00:00", $array["created_at"]);
        $this->assertSame("2025-11-07 11:00:00", $array["updated_at"]);
    }

    public function testShouldReturnEmptyStringsForNullDatesInArray(): void
    {
        $company = new Company();
        $company->setId(7);
        $company->setUserId(1);
        $company->setPublicName("Empresa Sem Datas");
        $company->setLegalName("Empresa LTDA");
        $company->setDocument("08.860.488/0001-39");
        $company->setDateFoundation("2015-10-10");

        $createdAt = new \DateTimeImmutable("2025-11-07 10:00:00");
        $updatedAt = new \DateTimeImmutable("2025-11-07 11:00:00");

        $company->setCreatedAt($createdAt);
        $company->setUpdatedAt($updatedAt);

        $array = $company->toArray();

        $this->assertSame('', $array["created_at"]);
        $this->assertSame('', $array["updated_at"]);
    }

    public function testShouldThrowExceptionForInvalidCnpj(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("O cnpj é inválido.");

        $company = new Company();
        $company->setDocument("abc123"); // inválido
    }
}