<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use Source\Domain\Exceptions\ValueObjects\CpfInvalidException;

final class Cpf
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        if (!$this->validate($cpf)) {
            throw new CpfInvalidException("O cpf é inválido.");
        }

        $this->cpf = $cpf;
    }

    public function validate(string $cpf): bool
    {
        // Extrai somente os números
        $cpf = preg_replace('/\D/', '', $cpf);

        if ($cpf === null || strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Converte para array de inteiros
        $cpfArray = array_map('intval', str_split($cpf));

        // Valida dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpfArray[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpfArray[$t] !== $d) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->cpf;
    }
}
