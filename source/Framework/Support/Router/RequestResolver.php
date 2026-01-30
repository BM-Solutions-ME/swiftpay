<?php

declare(strict_types=1);

namespace Source\Framework\Support\Router;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

final class RequestResolver
{
    /**
     * @param array<string, mixed> $payload
     * @return object|array|null
     */
    public static function resolve(object $controller, string $method, array $payload): object|array|null
    {
        $reflection = new ReflectionMethod($controller, $method);

        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType) {
                $arguments[] = null;
                continue;
            }

            $typeName = $type->getName();

            // built-in (int, string, etc)
            if ($type->isBuiltin()) {
                $arguments[] = $payload[$parameter->getName()] ?? null;

                if (is_array($payload)) {
                    $arguments = $payload ?? null;
                }
                continue;
            }

            // class → auto DTO bind
            $arguments = self::hydrate($typeName, $payload);
        }

        return $arguments;
    }

    /**
     * @param class-string $class
     */
    private static function hydrate(string $class, array $payload): object
    {
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $value = $payload[$name] ?? null;

            $params[] = self::cast($param->getType()?->getName(), $value);
        }

        return $reflection->newInstanceArgs($params);
    }

    private static function cast(?string $type, mixed $value): mixed
    {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOL),
            'string' => (string) $value,
            default => $value,
        };
    }
}
