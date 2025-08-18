<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

final class AuthInputData
{
    private Email $email;
    private string $password;

    /**
     * @param Email $email
     * @param string $password
     * @throws PasswordInvalidDigitsNumber
     */
    public function __construct(Email $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
        // validar password
        (new Password($this->password));
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}