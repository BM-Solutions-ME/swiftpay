<?php

namespace Source\Infra\Adapters;

use Source\Domain\Contracts\NotificationInterface;
use Twilio\Rest\Client;

class SmsAdapter implements NotificationInterface
{
    private string $id;
    private string $token;
    private string $to;
    private string $from;
    private string $message;

    /**
     * @param array<string, mixed> $params
    */
    public function __construct(array $params)
    {
        $this->id = $_ENV["TWILIO_ID"];
        $this->token = $_ENV["TWILIO_TOKEN"];

        $this->to = $params["to"];
        $this->from = $params["from"];
        $this->message = $params["message"];
    }

    public function send(): bool
    {
        $service = new Client($this->id, $this->token);
        $service->messages->create($this->to, [
            "from" => $this->from,
            "body" => $this->message
        ]);
        return true;
    }
}