<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\NewWallet;

use Source\Domain\Entities\Wallet;

final class NewWalletOutputData
{
    private readonly int $id;
    private readonly int $userId;
    private readonly ?int $companyId;
    private readonly string $title;
    private readonly int $balance;
    private readonly string $createdAt;

    /**
     * @param Wallet $data
    */
    public function __construct(Wallet $data)
    {
        $this->id = (int) $data->getId();
        $this->userId = $data->getUserId();
        $this->companyId = (!empty($data->getCompanyId()) ? $data->getCompanyId() : null);
        $this->title = $data->getTitle();
        $this->balance = $data->getBalance();
        $this->createdAt = (!empty($data->getCreatedAt()) ? $data->getCreatedAt()->format("Y-m-d H:i:s") : '');
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
            "title" => $this->title,
            "balance" => $this->balance,
            "created_at" => (!empty($this->createdAt)
                ? (new \DateTime($this->createdAt))->format('d/m/Y H\hi')
                : '')
        ];
    }
}