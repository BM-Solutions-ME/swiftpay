<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Exception;
use Source\Domain\Entities\User;
use Source\Domain\Repositories\AuthRepositoryInterface;
use Source\Framework\Core\Connect;
use Source\Framework\Support\Orm\Strategy\RepositoryStrategy;
use Source\Infra\Database\Handler\MariaDbRepositoryHandler;

final class AuthRepository implements AuthRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function execute(string $email, string $password): array
    {
        $repo = new RepositoryStrategy(new MariaDbRepositoryHandler(Connect::getInstance()));
        /** @var User|null $user */
        $user = $repo->query(User::class)
            ->where("email", "=", $email)
            ->get();

        if (empty($user)) {
            throw new Exception("O e-mail informado não está cadatrado.");
        }

        if (!passwd_verify($password, (string) $user->getPassword())) {
            throw new Exception("A senha informada está incorreta.");
        }

        if ($user->getStatus()->value !== "active") {
            $exceptionMessage = match($user->getStatus()->value) {
                "registered" => "O usuário cadastrado necessita ser verificado para obter permissão de acesso.",
                "inactive" => "O usuário está inativo.",
                "banned" => "O usuário foi banido.",
                "canceled" => "O usuário foi cancelado."
            };
            throw new Exception($exceptionMessage);
        }

        return $user->toArray();
    }
}