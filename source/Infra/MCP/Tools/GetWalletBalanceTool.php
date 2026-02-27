<?php

declare(strict_types=1);

namespace Source\Infra\MCP\Tools;

use Source\App\Usecases\Wallet\GetBalance\GetBalanceInput;
use Source\App\Usecases\Wallet\GetBalance\GetBalanceUsecase;
use Source\Infra\Repositories\WalletRepository;

final class GetWalletBalanceTool
{
    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function handle(array $input, int $userId): array
    {
        $usecase = new GetBalanceUsecase(new WalletRepository());

        // Sempre retorna o saldo total do usuário autenticado.
        $balance = $usecase->handle(
            new GetBalanceInput(
                'user',
                $userId
            )
        );

        return [
            'user_id' => $userId,
            'balance' => $balance,
        ];
    }
}

