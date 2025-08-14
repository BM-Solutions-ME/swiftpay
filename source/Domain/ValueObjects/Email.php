<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use Source\Domain\Exceptions\ValueObjects\EmailInvalidException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailInvalidException("Informe um e-mail válido!");
        }

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
