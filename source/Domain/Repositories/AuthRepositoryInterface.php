<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface AuthRepositoryInterface
{
    /** @return User */
    public function execute(string $email, string $password): User;
}