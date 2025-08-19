<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\User;
use Source\Domain\Enum\UserStatusEnum;
use Source\Domain\Repositories\UserRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class UserRepository implements UserRepositoryInterface
{

    /**
     * @param int $user_id
     * @return User
    */
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

    /**
     * @param int $user_id
     * @return boolean
    */
    public function validateNewAccount(int $user_id): bool
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var User|null $user */
        $user = $repo->find(User::class, $user_id);

        if (empty($user)) {
            throw new \Exception("O usuário não existe ou foi removido recentemente.");
        }

        if ($user->getStatus() !== UserStatusEnum::Registered) {
            throw new \Exception("O usuário já foi validado anteriormente.");
        }

        $user->setStatus(UserStatusEnum::Active);
        $repo->save($user);
        return true;
    }
}