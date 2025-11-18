<?php

declare(strict_types=1);

namespace unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Source\Domain\Exceptions\ValueObjects\CpfInvalidException;
use Source\Domain\ValueObjects\Cpf;

final class CpfTest extends TestCase
{
    public function testShouldCreateValidCpf(): void
    {
        // Example of a real valid CPF
        $validCpf = '529.982.247-25';

        $cpf = new Cpf($validCpf);

        $this->assertInstanceOf(Cpf::class, $cpf);
        $this->assertSame('529.982.247-25', (string)$cpf);
    }

    public function testShouldThrowExceptionForInvalidLengthCpf(): void
    {
        $this->expectException(CpfInvalidException::class);
        $this->expectExceptionMessage('O cpf é inválido.');

        new Cpf('123456'); // too short
    }

    public function testShouldThrowExceptionForRepeatedDigitsCpf(): void
    {
        $this->expectException(CpfInvalidException::class);
        $this->expectExceptionMessage('O cpf é inválido.');

        new Cpf('111.111.111-11');
    }

    public function testShouldThrowExceptionForInvalidVerificationDigits(): void
    {
        $this->expectException(CpfInvalidException::class);
        $this->expectExceptionMessage('O cpf é inválido.');

        new Cpf('529.982.247-99'); // incorrect verification digits
    }

    public function testShouldAcceptCpfWithoutFormatting(): void
    {
        $rawCpf = '52998224725';

        $cpf = new Cpf($rawCpf);

        $this->assertInstanceOf(Cpf::class, $cpf);
        $this->assertSame('52998224725', (string)$cpf);
    }

    public function testValidateShouldReturnFalseForInvalidCpf(): void
    {
        $cpf = new Cpf('52998224725'); // valid one just to instantiate the object

        $this->assertFalse($cpf->validate('12345678900'));
    }

    public function testValidateShouldReturnTrueForValidCpf(): void
    {
        $cpf = new Cpf('52998224725'); // valid

        $this->assertTrue($cpf->validate('52998224725'));
    }
}