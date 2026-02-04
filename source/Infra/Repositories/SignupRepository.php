<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\User;
use Source\Domain\Repositories\SignupRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class SignupRepository implements SignupRepositoryInterface
{
    /**
     * @param User $newUser
     * @return User
    */
    public function register(User $newUser): User
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var User $userRegistered */
        $userRegistered = $repo->save($newUser);
        return $userRegistered;
    }
}