<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

final class NewWalletOutputData
{
    private readonly int $id;
    private readonly int $userId;
    private readonly ?int $companyId;
    private readonly int $balance;
    private readonly string $createdAt;

    /**
     * @param array<string, mixed> $data
    */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->userId = $data["user_id"];
        $this->companyId = (!empty($data["company_id"]) ? $data["company_id"] : null);
        $this->balance = $data["balance"];
        $this->createdAt = $data["created_at"];
    }

    /**
     * @return array<string, mixed>
    */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->userId,
            "company_id" => $this->companyId,
            "balance" => $this->balance,
            "created_at" => (!empty($this->createdAt)
                ? (new \DateTime($this->createdAt))->format('d/m/Y H\hi')
                : '')
        ];
    }
}