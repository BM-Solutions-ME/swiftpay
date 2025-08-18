<?php

declare(strict_types=1);

namespace Source\App\Usecases\User\ValidateNewAccount;

use Source\Domain\Enum\UserStatusEnum;

final class ValidateNewAccountInputData
{
    private int $user_id;
    private string $secret;

    private UserStatusEnum $userStatus;
    public function __construct(
        private readonly string $userHash
    )
    {
        $hash = base64_decode($this->userHash);
        /** @var array<string, mixed> $dataUserValidate */
        $dataUserValidate = (json_validate($hash) ? json_decode($hash, true) : []);

        if (!in_array(["user_id", "secret"], $dataUserValidate)) {
            throw new \Exception("Ocorreu um erro inesperado.");
        }

        $this->user_id = $dataUserValidate["user_id"];
        $this->secret = $dataUserValidate["secret"];
        $this->userStatus = UserStatusEnum::from($dataUserValidate["user_status"]);
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getUserStatus(): UserStatusEnum
    {
        return $this->userStatus;
    }
}