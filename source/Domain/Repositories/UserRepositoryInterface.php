<?php

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface UserRepositoryInterface
{
    /**
     * @param int $user_id
     * @return boolean
    */
    public function validateNewAccount(int $user_id): bool;

    /**
     * @param int $user_id
     * @return User
    */
    public function getUserById(int $user_id): User;
}