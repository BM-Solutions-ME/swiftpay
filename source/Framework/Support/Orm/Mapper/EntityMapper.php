<?php

namespace Source\Framework\Support\Orm\Mapper;

use DateTime;
use ReflectionClass;
use ReflectionProperty;
use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;

class EntityMapper
{
    /**
     * @param object|class-string $entity
     * @return string
     */
    public function getTableName(object|string $entity): string
    {
        $class = new ReflectionClass($entity);
        $attributes = $class->getAttributes(Table::class);

        if (empty($attributes)) {
            throw new \RuntimeException("Missing #[Table] attribute on " . $class->getName());
        }

        return $attributes[0]->newInstance()->name;
    }

    /**
     * @return array<string, mixed>
    */
    public function getColumnMappings(object $entity): array
    {
        $reflection = new ReflectionClass($entity);
        $mappings = [];

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $getCallerMethod = $backtrace[1]['function'];

        foreach (
            $reflection->getProperties(
                ReflectionProperty::IS_PRIVATE
                | ReflectionProperty::IS_PROTECTED
                | ReflectionProperty::IS_PUBLIC)
            as $property
        ) {
            $columnAttr = $property->getAttributes(Column::class);
            if (!empty($columnAttr)) {
                $columnInstance = $columnAttr[0]->newInstance();
                $column = $columnAttr[0]->newInstance()->name;
                $property->setAccessible(true);

                // Verifica se a propriedade foi inicializada
                if (in_array($getCallerMethod, ['create', 'update', 'save'])) {
                    if ($columnInstance->primaryKey && !$property->isInitialized($entity)) {
                        continue;
                    }

                    if ($columnInstance->protected) {
                        continue;
                    }

                    if (!$property->isInitialized($entity)) {
                        $mappings[$column] = null;
                        continue;
                    }
                }

                $value = $property->getValue($entity);
                $mappings[$column] = $value;
            }
        }

        return $mappings;
    }

    /**
     * @return array<string, mixed>
    */
    public function getColumnAnnotations(object $entity): array
    {
        $reflection = new ReflectionClass($entity);
        $mappings = [];

        foreach (
            $reflection->getProperties(
                ReflectionProperty::IS_PRIVATE
                | ReflectionProperty::IS_PROTECTED
                | ReflectionProperty::IS_PUBLIC)
            as $property
        ) {
            $columnAttr = $property->getAttributes(Column::class);
            if (!empty($columnAttr)) {
                $columnInstance = $columnAttr[0]->newInstance();
                $column = $columnAttr[0]->newInstance()->name;
                $property->setAccessible(true);

                $mappings[$column] = $columnInstance;
            }
        }

        return $mappings;
    }

    /**
     * @param string $className
     * @param array<string, mixed> $row
    */
    public function hydrate(string $className, array $row): object
    {
        /** @var class-string $className */
        $ref = new \ReflectionClass($className);
        $instance = $ref->newInstanceWithoutConstructor();

        foreach ($ref->getProperties() as $property) {
            $columnAttr = $property->getAttributes(Column::class);
            if (empty($columnAttr)) {
                continue;
            }

            if ($columnAttr[0]->newInstance()->noHydrate) {
                continue;
            }

            $column = $columnAttr[0]->newInstance()->name;

            if (!array_key_exists($column, $row)) {
                continue;
            }

            $value = $row[$column];
            $setter = 'set' . ucfirst($property->getName());

            if ($ref->hasMethod($setter)) {
                $method = $ref->getMethod($setter);
                if ($method->isPublic()) {
                    if ($columnAttr[0]->newInstance()->cast === 'DateTime') {
                        $value = new DateTime($value);
                    } elseif (enum_exists($columnAttr[0]->newInstance()->cast)) {
                        /** @var \UnitEnum $instanceEnum */
                        $instanceEnum = $columnAttr[0]->newInstance()->cast;
                        $value = $instanceEnum::from($value);
                    }

                    $method->invoke($instance, $value);
                    continue;
                }
            }

            // fallback direto (se não houver setter)
            $property->setAccessible(true);
            $property->setValue($instance, $value);
        }

        return $instance;
    }
}