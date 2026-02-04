<?php

declare(strict_types=1);

namespace Source\App\Usecases\Notify;

use Source\Domain\Contracts\NotificationInterface;

final class NotifyUsecase
{
    public function __construct(
        private readonly NotificationInterface $notifier
    ) {}

    public function handle(): bool
    {
        return $this->notifier->send();
    }
}