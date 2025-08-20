<?php

declare(strict_types=1);

namespace Source\Domain\Http;

use Source\Domain\Http\Enum\HttpStatusEnum;

/**
 *
 */
abstract class HttpException extends \Exception
{
    /**
     * @param string $message
     * @param HttpStatusEnum $status
     * @param array<string, mixed> $details
     */
    public function __construct(
        string $message,
        private readonly HttpStatusEnum $status,
        private readonly array $details = []
    ) {
        parent::__construct($message);
    }

    /**
     * @return HttpStatusEnum
     */
    public function getStatus(): HttpStatusEnum
    {
        return $this->status;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}