<?php

declare(strict_types=1);

namespace unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Source\Domain\Exceptions\ValueObjects\EmailInvalidException;
use Source\Domain\ValueObjects\Email;

final class EmailTest extends TestCase
{
    public function testShouldCreateValidEmail(): void
    {
        $emailAddress = 'user@example.com';

        $email = new Email($emailAddress);

        $this->assertInstanceOf(Email::class, $email);
        $this->assertSame('user@example.com', (string)$email);
    }

    public function testShouldThrowExceptionForInvalidEmail(): void
    {
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        new Email('invalid-email');
    }

    public function testShouldThrowExceptionForEmptyEmail(): void
    {
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        new Email('');
    }

    public function testShouldThrowExceptionForMissingDomain(): void
    {
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        new Email('user@');
    }

    public function testShouldThrowExceptionForMissingAtSymbol(): void
    {
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        new Email('userexample.com');
    }

    public function testShouldBeCaseInsensitive(): void
    {
        $email = new Email('USER@EXAMPLE.COM');

        $this->assertSame('USER@EXAMPLE.COM', (string)$email);
    }
}