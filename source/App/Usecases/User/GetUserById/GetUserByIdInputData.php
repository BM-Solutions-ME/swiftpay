<?php

declare(strict_types=1);

namespace Source\App\Usecases\User\GetUserById;

final class GetUserByIdInputData
{
    public function __construct(
        private readonly int $user_id
    ) {}

    public function getUserId(): int
    {
        return $this->user_id;
    }
}