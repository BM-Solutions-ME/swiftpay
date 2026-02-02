<?php

declare(strict_types=1);

namespace Source\App\Contracts;

interface TransferAuthorizer
{
    public function authorize(): bool;
}