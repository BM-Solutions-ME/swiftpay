<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\Transfer;

interface TransferRepositoryInterface
{
    public function execute(Transfer $data): Transfer;
}