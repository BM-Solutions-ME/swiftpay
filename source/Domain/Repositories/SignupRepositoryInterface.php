<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface SignupRepositoryInterface
{
    /** @return array<string, mixed> */
    public function register(User $newUser): array;
}