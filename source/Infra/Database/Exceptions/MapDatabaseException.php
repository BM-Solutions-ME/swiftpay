<?php

declare(strict_types=1);

namespace Source\Infra\Database\Exceptions;

use Source\Infra\Database\Enum\DatabaseSqlErrorEnum;

class MapDatabaseException
{
    public static function map(\PDOException $e): string
    {
        $sqlState = $e->getCode();          // SQLSTATE (ex: 23000)
        $nativeCode = (!empty($e->errorInfo[1]) ? (string) $e->errorInfo[1] : ''); // Código específico do MariaDB/MySQL

        return match ($nativeCode) {
            DatabaseSqlErrorEnum::DUPLICATE_ENTRY->value   => 'Já existe um registro com esse valor.',
            DatabaseSqlErrorEnum::FK_CONSTRAINT->value,
            DatabaseSqlErrorEnum::FK_CONSTRAINT_ADD->value => 'Não é possível excluir/alterar pois existem registros relacionados.',
            DatabaseSqlErrorEnum::NOT_NULL->value          => 'Campo obrigatório não pode ser nulo.',
            DatabaseSqlErrorEnum::CHECK_CONSTRAINT->value  => 'Valor inválido para esta coluna.',
            DatabaseSqlErrorEnum::LOCK_TIMEOUT->value      => 'Tempo de bloqueio excedido, tente novamente.',
            default => "Erro de banco de dados: " . $e->getMessage(),
        };
    }
}