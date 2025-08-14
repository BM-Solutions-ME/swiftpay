<?php

declare(strict_types=1);

namespace unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\User;
use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Enum\UserTypeEnum;
use Source\Domain\Exceptions\ValueObjects\CpfInvalidException;
use Source\Domain\Exceptions\ValueObjects\EmailInvalidException;
use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;

final class UserTest extends TestCase
{
    public function testInvalidDocument()
    {
        $this->expectException(CpfInvalidException::class);
        $this->expectExceptionMessage('O cpf é inválido.');

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType(UserTypeEnum::Person);
        $user->setDocument('000.000.000-00');
        $user->setEmail("hector.bonilla@bluware.com.br");
        $user->setStatus(UserStatusEnum::Registered);
    }
    public function testInvalidEmail()
    {
        // Espera que uma DomainException seja lançada
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType(UserTypeEnum::Person);
        $user->setEmail("hector.bonilla_bluware.com.br");
        $user->setStatus(UserStatusEnum::Registered);
    }

    public function testPasswordInvalidMinDigitNumber()
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType(UserTypeEnum::Person);
        $user->setEmail("hector.bonilla@bluware.com.br");
        $user->setPassword("1234567");
        $user->setStatus(UserStatusEnum::Registered);
    }

    public function testPasswordInvalidMaxDigitNumber()
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType(UserTypeEnum::Person);
        $user->setEmail("hector.bonilla@bluware.com.br");
        $user->setPassword("123456789012345678901");
        $user->setStatus(UserStatusEnum::Registered);
    }
}