<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface SignupRepositoryInterface
{
    /** @return User */
    public function register(User $newUser): User;
}