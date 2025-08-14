<?php

namespace Source\Domain\Contracts;

interface PersistableEntityInterface
{
    public function getId(): int|null;
    public function setId(int $id): void;
}