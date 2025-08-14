<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;

final class Password
{
    private string $password;

    public function __construct(string $password)
    {
        if (self::isHashed($password)) {
            $this->password = $password;
            return;
        }

        if (strlen($password) < CONF_PASSWD_MIN_LEN || strlen($password) > CONF_PASSWD_MAX_LEN) {
            throw new PasswordInvalidDigitsNumber(
                "A senha deve ter entre " . CONF_PASSWD_MIN_LEN . " e " . CONF_PASSWD_MAX_LEN . " dígitos."
            );
        }

        $this->password = passwd($password);
    }

    public function __toString(): string
    {
        return $this->password;
    }

    private static function isHashed(string $value): bool
    {
        return preg_match('/^\$2[aby]\$(\d{2})\$[\.\/A-Za-z0-9]{53}$/', $value) === 1;
    }
}
