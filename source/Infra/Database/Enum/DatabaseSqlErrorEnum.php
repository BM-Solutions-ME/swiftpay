<?php

declare(strict_types=1);

namespace Source\Infra\Database\Enum;

enum DatabaseSqlErrorEnum: string
{
    // SQLSTATE genéricos
    case INTEGRITY_CONSTRAINT_VIOLATION = '23000';
    case DEADLOCK                       = '40001';
    case GENERAL_ERROR                  = 'HY000';

    // Códigos nativos (MySQL/MariaDB error codes)
    case DUPLICATE_ENTRY   = '1062';  // Violação UNIQUE / PK
    case FK_CONSTRAINT     = '1451';  // Violação FK - cannot delete/update parent
    case FK_CONSTRAINT_ADD = '1452';  // Violação FK - cannot add/update child
    case NOT_NULL          = '1048';  // Coluna NOT NULL sem valor
    case CHECK_CONSTRAINT  = '4025';  // MariaDB >= 10.2 CHECK constraint
    case LOCK_TIMEOUT      = '1205';  // Lock wait timeout exceeded
}
