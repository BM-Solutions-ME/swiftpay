<?php

declare(strict_types=1);

namespace Source\Domain\Enum;

enum UserStatusEnum: string
{
    case Registered = "registered";
    case Active = "active";
    case Inactive = "inactive";
    case Banned = "banned";
    case Canceled = "canceled";
}
