<?php

declare(strict_types=1);

namespace Source\Domain\Enum;

enum TransferStatusEnum: string
{
    case PENDING   = 'pending';   // transferência criada, aguardando processamento
    case PROCESSING = 'processing'; // em andamento
    case COMPLETED = 'completed'; // concluída com sucesso
    case FAILED    = 'failed';    // falha no processamento
    case CANCELED  = 'canceled';  // cancelada pelo usuário ou sistema

    /**
     * Verifica se a transferência terminou (sucesso, falha ou cancelada).
     *
     * @return boolean
     */
    public function isFinal(): bool
    {
        return match ($this) {
            self::COMPLETED,
            self::FAILED,
            self::CANCELED => true,
            default => false,
        };
    }

    /**
     * Retorna se está em andamento.
     *
     * @return boolean
     */
    public function isOngoing(): bool
    {
        return $this === self::PENDING || $this === self::PROCESSING;
    }
}
