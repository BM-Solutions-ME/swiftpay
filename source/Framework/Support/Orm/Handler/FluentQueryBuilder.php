<?php

declare(strict_types=1);

namespace Source\Framework\Support\Orm\Handler;

use PDO;
use Source\Domain\Attributes\Column;
use Source\Framework\Support\Orm\Mapper\EntityMapper;

class FluentQueryBuilder
{
    private string $entityClass;
    private string $entityAlias;
    private string $columns = "*";
    private string $order;
    private string $group;
    private string $limit;
    private string $offset;
    /** @var array<int, mixed> $conditions */
    private array $conditions = [];
    /** @var array<int, mixed> $selects */
    private array $selects = [];
    /** @var array<int, mixed> $queryJoin */
    private array $queryJoin = [];

    public function __construct(
        private readonly PDO $pdo,
        private readonly EntityMapper $mapper
    ) {}

    public function for(string $entityClass, string $alias = ''): self
    {
        $this->selects[] = [
            "class" => $entityClass,
            "prefix" => $alias
        ];

        $this->entityClass = $entityClass;
        $this->entityAlias = $alias;
        return $this;
    }

    public function columns(string $columns = "*"): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->conditions[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function andWhere(string $column, string $operator, mixed $value): self
    {
        return $this->where($column, $operator, $value);
    }

    public function order(string $columnOrder): self
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    public function group(string $columnGroup): self
    {
        $this->group = " GROUP BY {$columnGroup}";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param boolean $all
     * @return object|array<int, object>|null
    */
    public function get(bool $all = false): array|object|null
    {
        $dummy = new $this->entityClass();
        $table = $this->mapper->getTableName($dummy);

        $sql = "SELECT {$this->columns} FROM `$table`";

        $params = [];
        $whereClauses = [];

        foreach ($this->conditions as $i => $cond) {
            $param = "param_$i";
            $whereClauses[] = "`{$cond['column']}` {$cond['operator']} :$param";
            $params[$param] = $cond['value'];
        }

        if ($whereClauses) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        if (!empty($this->group)) {
            $sql .= $this->group;
        }

        if (!empty($this->order)) {
            $sql .= $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= $this->limit;
        }

        if (!empty($this->offset)) {
            $sql .= $this->offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($all) {
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $this->mapper->hydrate($this->entityClass, $row);
            }

            return $results;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row ? $this->mapper->hydrate($this->entityClass, $row) : null);
    }

    /**
     * @return array<int, mixed>
    */
    public function getJoin(): array
    {
        $dummy = new $this->entityClass();
        $table = $this->mapper->getTableName($dummy);

        $selectParts = [];

        foreach ($this->selects as $entry) {
            $entity = new ($entry['class'])();
            $alias = $entry['prefix'];

            foreach ((new \ReflectionClass($entity))->getProperties() as $property) {
                $attrs = $property->getAttributes(Column::class);
                if (empty($attrs)) {
                    continue;
                }

                $column = $attrs[0]->newInstance()->name;
                $selectParts[] = sprintf('%s.%s AS %s_%s', $alias, $column, $alias, $column);
            }
        }

        $sql = "SELECT " . implode(', ', $selectParts) .  " FROM `$table` {$this->entityAlias}";

        if (!empty($this->queryJoin)) {
            foreach ($this->queryJoin as $join) {
                $sql .= " " . $join["query"];
            }
        }

        $params = [];
        $whereClauses = [];

        foreach ($this->conditions as $i => $cond) {
            $param = "param_$i";
            $whereClauses[] = "`{$cond['column']}` {$cond['operator']} :$param";
            $params[$param] = $cond['value'];
        }

        if ($whereClauses) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        if (!empty($this->group)) {
            $sql .= $this->group;
        }

        if (!empty($this->order)) {
            $sql .= $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= $this->limit;
        }

        if (!empty($this->offset)) {
            $sql .= $this->offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $split = $this->splitRowByPrefix($row);
            $hydrated = [];

            foreach ($this->selects as $entry) {
                $alias = $entry['prefix'];
                $class = $entry['class'];

                if (!isset($split[$alias])) {
                    continue;
                }

                $hydrated[$alias] = $this->mapper->hydrate($class, $split[$alias]);
            }

            $results[] = $hydrated;
        }

        return $results;
    }

    public function join(string $entity, string $alias, string $term): self
    {
        return $this->setQueryJoin($entity, 'JOIN', $alias, $term);
    }

    public function innerJoin(string $entity, string $alias, string $term): self
    {
        return $this->setQueryJoin($entity, 'INNER JOIN', $alias, $term);
    }

    public function leftJoin(string $entity, string $alias, string $term): self
    {
        return $this->setQueryJoin($entity, 'LEFT JOIN', $alias, $term);
    }

    public function rightJoin(string $entity, string $alias, string $term): self
    {
        return $this->setQueryJoin($entity, 'RIGHT JOIN', $alias, $term);
    }

    public function fullJoin(string $entity, string $alias, string $term): self
    {
        return $this->setQueryJoin($entity, 'FULL JOIN', $alias, $term);
    }

    public function crossJoin(string $entity, string $alias): self
    {
        return $this->setQueryJoin(entity: $entity, type: 'CROSS JOIN', alias: $alias);
    }

    public function naturalJoin(string $entity, string $alias): self
    {
        return $this->setQueryJoin(entity: $entity, type: 'NATURAL JOIN', alias: $alias);
    }

    private function setQueryJoin(string $entity, string $type, string $alias, ?string $term = null): self
    {
        $dummy = new $entity();
        $tableName = $this->mapper->getTableName($dummy);

        $this->selects[] = [
            "class" => $entity,
            "prefix" => $alias
        ];

        switch ($type) {
            case 'JOIN':
            case 'INNER JOIN':
            case 'LEFT JOIN':
            case 'RIGHT JOIN':
            case 'FULL JOIN':
                $this->queryJoin[] = [
                    "class" => $entity,
                    "prefix" => $alias,
                    "query" => "{$type} {$tableName} {$alias} ON {$term}"
                ];
                break;

            case 'CROSS JOIN':
            case 'NATURAL JOIN':
                $this->queryJoin[] = [
                    "class" => $entity,
                    "prefix" => $alias,
                    "query" => "{$type} {$tableName}"
                ];
                break;
            default:
                $this->queryJoin = [];
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
    */
    private function splitRowByPrefix(array $row): array
    {
        $entities = [];

        foreach ($row as $key => $value) {
            if (strpos($key, '_') === false) {
                continue;
            }

            [$prefix, $column] = explode('_', $key, 2);
            $entities[$prefix][$column] = $value;
        }

        return $entities;
    }
}