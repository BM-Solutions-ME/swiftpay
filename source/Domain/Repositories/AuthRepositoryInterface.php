<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

interface AuthRepositoryInterface
{
    /** @return array<string, mixed> */
    public function execute(string $email, string $password): array;
}