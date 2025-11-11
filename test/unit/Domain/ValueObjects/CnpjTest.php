<?php

declare(strict_types=1);

namespace unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Source\Domain\Exceptions\ValueObjects\CnpjInvalidException;
use Source\Domain\ValueObjects\Cnpj;

final class CnpjTest extends TestCase
{
    public function testShouldCreateValidCnpj(): void
    {
        // Exemplo real válido: 45.723.174/0001-10
        $validCnpj = "45723174000110";

        $cnpj = new Cnpj($validCnpj);

        $this->assertInstanceOf(Cnpj::class, $cnpj);
        $this->assertSame($validCnpj, (string)$cnpj);
    }

    public function testShouldAcceptCnpjWithFormatting(): void
    {
        $formatted = "45.723.174/0001-10";
        $cnpj = new Cnpj($formatted);

        $this->assertSame("45723174000110", (string)$cnpj);
    }

    public function testShouldThrowExceptionForInvalidCnpj(): void
    {
        $this->expectException(CnpjInvalidException::class);
        $this->expectExceptionMessage("O cnpj é inválido.");

        new Cnpj("12345678000100"); // Inválido
    }

    public function testShouldThrowExceptionForCnpjWithWrongLength(): void
    {
        $this->expectException(CnpjInvalidException::class);
        new Cnpj("12345678"); // Menos de 14 dígitos
    }

    public function testShouldThrowExceptionForRepeatedDigits(): void
    {
        $this->expectException(CnpjInvalidException::class);
        new Cnpj("11111111111111"); // Dígitos repetidos
    }

    public function testValidateShouldReturnTrueForValidCnpj(): void
    {
        $cnpj = new Cnpj("45723174000110");
        $this->assertTrue($cnpj->validate("45723174000110"));
    }

    public function testValidateShouldReturnFalseForInvalidCnpj(): void
    {
        $cnpj = new Cnpj("45723174000110");
        $this->assertFalse($cnpj->validate("00000000000000"));
    }

    public function testToStringReturnsRawCnpj(): void
    {
        $cnpj = new Cnpj("45.723.174/0001-10");
        $this->assertSame("45723174000110", (string)$cnpj);
    }
}