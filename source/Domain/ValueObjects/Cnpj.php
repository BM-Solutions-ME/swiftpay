<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use Source\Domain\Exceptions\ValueObjects\CnpjInvalidException;

final class Cnpj
{
    private string $cnpj;

    public function __construct(string $cnpj)
    {
        if (!$this->validate($cnpj)) {
            throw new CnpjInvalidException("O cnpj é inválido.");
        }

        $this->cnpj = $cnpj;
    }

    public function validate(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if ($cnpj === null || strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // Converte string para array de inteiros
        $cnpjArray = array_map('intval', str_split($cnpj));

        // Valida primeiro dígito verificador
        $soma = 0;
        $j = 5;
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpjArray[$i] * $j;
            $j = ($j === 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cnpjArray[12] !== $digito1) {
            return false;
        }

        // Valida segundo dígito verificador
        $soma = 0;
        $j = 6;
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpjArray[$i] * $j;
            $j = ($j === 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return $cnpjArray[13] === $digito2;
    }

    public function __toString(): string
    {
        return $this->cnpj;
    }
}
