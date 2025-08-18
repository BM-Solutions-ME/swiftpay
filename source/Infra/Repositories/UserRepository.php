<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\User;
use Source\Domain\Repositories\UserRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class UserRepository implements UserRepositoryInterface
{

    public function getUserById(int $user_id): User
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var User|null $user */
        $user = $repo->find(User::class, $user_id);

        if (empty($user)) {
            throw new \Exception("O usuário não existe ou foi removido recentemente.");
        }

        return $user;
    }
}