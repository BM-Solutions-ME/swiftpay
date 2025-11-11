<?php

declare(strict_types=1);

namespace unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;
use Source\Domain\ValueObjects\Password;

final class PasswordTest extends TestCase
{
    protected function setUp(): void
    {
        // Define constants for test environment if not already defined
        if (!defined('CONF_PASSWD_MIN_LEN')) {
            define('CONF_PASSWD_MIN_LEN', 6);
        }
        if (!defined('CONF_PASSWD_MAX_LEN')) {
            define('CONF_PASSWD_MAX_LEN', 20);
        }

        // Define a mock global passwd() helper if not available
        if (!function_exists('passwd')) {
            function passwd(string $password): string
            {
                return password_hash($password, PASSWORD_BCRYPT);
            }
        }
    }

    public function testShouldCreatePasswordWithValidLength(): void
    {
        $plainPassword = 'Secure123';
        $password = new Password($plainPassword);

        $this->assertInstanceOf(Password::class, $password);
        $this->assertNotSame($plainPassword, (string)$password); // should be hashed
        $this->assertTrue(password_verify($plainPassword, (string)$password));
    }

    public function testShouldThrowExceptionForTooShortPassword(): void
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);
        $this->expectExceptionMessage('A senha deve ter entre ' . CONF_PASSWD_MIN_LEN . ' e ' . CONF_PASSWD_MAX_LEN . ' dígitos.');

        new Password('123');
    }

    public function testShouldThrowExceptionForTooLongPassword(): void
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);
        $this->expectExceptionMessage('A senha deve ter entre ' . CONF_PASSWD_MIN_LEN . ' e ' . CONF_PASSWD_MAX_LEN . ' dígitos.');

        new Password(str_repeat('A', CONF_PASSWD_MAX_LEN + 1));
    }

    public function testShouldAcceptAlreadyHashedPassword(): void
    {
        $hashed = password_hash('MySecurePass', PASSWORD_BCRYPT);

        $password = new Password($hashed);

        $this->assertSame($hashed, (string)$password);
        $this->assertTrue(password_verify('MySecurePass', (string)$password));
    }

    public function testShouldGenerateDifferentHashesForSamePassword(): void
    {
        $plain = 'RepeatablePassword';

        $first = new Password($plain);
        $second = new Password($plain);

        $this->assertNotSame((string)$first, (string)$second);
    }

    public function testShouldRecognizeValidHashFormat(): void
    {
        $hash = password_hash('Secret', PASSWORD_BCRYPT);
        $ref = new \ReflectionClass(Password::class);
        $method = $ref->getMethod('isHashed');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(null, $hash));
        $this->assertFalse($method->invoke(null, 'notahash'));
    }
}