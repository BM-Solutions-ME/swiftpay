<?php

declare(strict_types=1);

namespace Source\Framework\Support;

final class InputSanitizer
{
    /**
     * Sanitiza recursivamente um array contra XSS e injeções básicas
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function sanitize(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            // Se o valor for um array, aplica recursão
            if (is_array($value)) {
                $clean[$key] = self::sanitize($value);
            } // Se for string, limpa
            elseif (is_string($value)) {
                $clean[$key] = htmlspecialchars(
                    trim($value),
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );
            } // Se for int/float/bool/null mantém
            elseif (is_int($value) || is_float($value) || is_bool($value) || $value === null) {
                $clean[$key] = $value;
            } // Qualquer outro tipo (ex: objeto), converte para string segura
            else {
                $clean[$key] = htmlspecialchars(
                    trim((string)$value),
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );
            }
        }

        return $clean;
    }
}
