<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use InvalidArgumentException;

final class Id
{
    private int|string $value;

    public function __construct(int|string $value)
    {
        if (!is_int($value) && !self::isValidUuid($value)) {
            throw new InvalidArgumentException('ID deve ser inteiro ou UUID válido.');
        }

        $this->value = $value;
    }

    public function get(): int|string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function isValidUuid(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid) === 1;
    }
}