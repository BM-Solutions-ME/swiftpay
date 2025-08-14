<?php

namespace Source\Domain\Contracts;

interface NotificationInterface
{
    public function send(): bool;
}