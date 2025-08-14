<?php

namespace Source\Framework\Support;

class CallbackHandler
{
    /** @var array<string, mixed> $response */
    private array $response;

    public function set(int $code, ?string $type = null, ?string $message = null, string $rule = "errors"): self
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array<string, mixed>|null $response
    */
    public function output(int $code = 200, ?array $response = null): void
    {
        if (!empty($response)) {
            http_response_code($code);
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}