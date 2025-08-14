<?php

declare(strict_types=1);

namespace Source\Infra\Adapters;

use Source\Domain\Contracts\NotificationInterface;
use Source\Framework\Support\Email;

final class EmailAdapter implements NotificationInterface
{
    private string $subject;
    private string $body;
    private string $recipient;
    private string $recipientName;

    /**
     * @param array<string, mixed> $params
    */
    public function __construct(array $params)
    {
        $this->subject = $params["subject"];
        $this->body = $params["body"];
        $this->recipient = $params["recipient"];
        $this->recipientName = $params["recipientName"];
    }

    public function send(): bool
    {
        $email = new Email();
        $email->bootstrap($this->subject, $this->body, $this->recipient, $this->recipientName);
        return $email->send();
    }
}