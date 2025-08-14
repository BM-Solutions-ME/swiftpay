<?php

declare(strict_types=1);

namespace Source\Infra\Repositories\Handler;

use DateTimeInterface;
use PDO;
use Source\Domain\Contracts\PersistableEntityInterface;
use Source\Infra\Repositories\Contract\QueryableRepositoryInterface;
use Source\Infra\Repositories\Contract\RepositoryHandlerInterface;
use Source\Infra\Repositories\Mapper\EntityMapper;

class MariaDbRepositoryHandler implements RepositoryHandlerInterface, QueryableRepositoryInterface
{
    private EntityMapper $mapper;

    public function __construct(
        private readonly PDO $pdo
    ) {
        $this->mapper = new EntityMapper;
    }

    public function find(string $entityClass, mixed $id): object|null {
        $dummy = new $entityClass();
        $table = $this->mapper->getTableName($dummy);
        $annotations = $this->mapper->getColumnAnnotations($dummy);

        // identifica o atributo primary key
        $primaryColumn = '';
        foreach ($annotations as $attribute => $annotation) {
            if ($annotation->primaryKey) {
                $primaryColumn = $attribute;
                break;
            }
        }

        $sql = sprintf("SELECT * FROM `%s` WHERE `%s` = :id LIMIT 1", $table, $primaryColumn);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapper->hydrate($entityClass, $row);
    }

    private function create(object $entity): int
    {
        $table = $this->mapper->getTableName($entity);
        $data = $this->mapper->getColumnMappings($entity);
        $annotations = $this->mapper->getColumnAnnotations($entity);

        $columns = array_keys($data);
        $placeholders = [];
        //$placeholders = array_map(fn($c) => ":$c", $columns);

        foreach ($data as $field => $value) {
            $annotationsField = $annotations[$field];

            // verifica campos obrigatórios
            if ($annotationsField->required && empty($value)) {
                throw new \Exception($annotationsField->requiredMessage);
            }

            if (!empty($value)) {
                //$placeholders[] = ($annotationsField->type == 'string' ? "':{$field}'" : ":{$field}");
                $placeholders[] = ":{$field}";
            } else {
                $placeholders[] = 'NULL';
            }
        }

        $columnsInsertString = implode(',', $columns);
        $placeholdersInsertString = implode(',', $placeholders);
        $sql = "INSERT INTO {$table} ({$columnsInsertString}) VALUES ({$placeholdersInsertString})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->filter($data));
        $rowId = $this->pdo->lastInsertId();

        if (!$rowId) {
            throw new \Exception("Não foi possível realizar essa operação.");
        }

        return (int) $rowId;
    }

    private function update(object $entity): int
    {
        $table = $this->mapper->getTableName($entity);
        $data = $this->mapper->getColumnMappings($entity);
        $annotations = $this->mapper->getColumnAnnotations($entity);

        $dataSet = [];
        $terms = [];

        foreach ($data as $field => $value) {
            $annotationsField = $annotations[$field];

            // verifica campos obrigatórios
            if ($annotationsField->required && empty($value)) {
                throw new \Exception($annotationsField->requiredMessage);
            }

            if (!$annotationsField->primaryKey) {
                if (!empty($value)) {
                    //$dataSet[] = ($annotationsField->type == 'string' ? "{$field} = ':{$field}'" : "{$field} = :{$field}");
                    $dataSet[] = "{$field} = :{$field}";
                } else {
                    $dataSet[] = "{$field} = NULL";
                }
            } else {
                $terms[] = "{$field} = :{$field}";
            }
        }

        $dataSetString = implode(', ', $dataSet);
        $termsString = implode(' AND ', $terms);
        $sql = "UPDATE {$table} SET {$dataSetString} WHERE {$termsString}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->filter($data));
        $rowCount = $stmt->rowCount();

        if ($rowCount == 0) {
            throw new \Exception("Não foi possível realizar essa operação.");
        }

        return $rowCount;
    }

    public function save(PersistableEntityInterface $entity): object|null
    {
        $reflection = new \ReflectionClass($entity);

        if (!$reflection->hasProperty('id')) {
            throw new \RuntimeException('A propriedade id não foi encontrada na entidade.');
        }

        $property = $reflection->getProperty('id');
        $property->setAccessible(true);

        $id = null;
        $isUpdate = true;
        // create
        if (!$property->isInitialized($entity)) {
            $id = $this->create($entity);
            $entity->setId($id);
            $isUpdate = false;
        }

        // update
        if ($isUpdate) {
            $id = $entity->getId();
            $this->update($entity);
        }

        return $this->find(get_class($entity), $id);
    }

    public function delete(object $entity): void {
        $table = $this->mapper->getTableName($entity);
        $data = $this->mapper->getColumnMappings($entity);

        $primaryColumn = array_key_first($data);
        $primaryValue = $data[$primaryColumn];

        $sql = sprintf("DELETE FROM `%s` WHERE `%s` = :id", $table, $primaryColumn);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $primaryValue]);
    }

    public function query(string $entityClass, string $alias = ''): FluentQueryBuilder
    {
        return (new FluentQueryBuilder($this->pdo, $this->mapper))->for($entityClass, $alias);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
    */
    private function filter(array $data): array
    {
        $dataFilteredArray = [];

        foreach ($data as $key => $value) {
            // Se o valor for um objeto com __toString(), converte
            if (is_null($value)) {
                continue;
            } elseif (is_string($value)
                || is_float($value)
                || (is_object($value) && method_exists($value, '__toString'))) {
                $dataFilteredArray[$key] = filter_var((string)$value, FILTER_DEFAULT);
            } elseif ($value instanceof DateTimeInterface) {
                $dataFilteredArray[$key] = $value->format('Y-m-d');
            } elseif (is_int($value)) {
                $dataFilteredArray[$key] = filter_var($value, FILTER_VALIDATE_INT);
            }
        }

        return $dataFilteredArray;
    }
}