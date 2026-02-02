<?php

declare(strict_types=1);

namespace Source\Framework\Support;

use Exception;
use Source\App\Contracts\CurlRequestInterface;

final class Http implements CurlRequestInterface
{
    private string $baseUrl;
    private string $method;
    /** @var array<int, mixed>|null $headers */
    private ?array $headers;
    /** @var array<string, mixed>|null $data */
    private ?array $data;

    private string $response;

    /**
     * @param array<int, mixed>|null $headers
     * @param array<string, mixed>|null $data
    */
    public function __construct(string $baseUrl = '', ?array $headers = null, ?array $data = null)
    {
        $this->setBaseUrl($baseUrl);
        $this->setHeaders($headers);
        $this->setData($data);
    }

    public function setBaseUrl(string $baseUrl): Http
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param array<int, mixed>|null $headers
     * @return Http
    */
    public function setHeaders(?array $headers): Http
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param array<string, mixed>|null $data
     * @return Http
    */
    public function setData(?array $data): Http
    {
        $this->data = $data;
        return $this;
    }

    public function get(): Http
    {
        $this->setMethod("get");
        $this->execute();
        return $this;
    }

    public function post(): Http
    {
        $this->setMethod("post");
        $this->execute();
        return $this;
    }

    public function put(): Http
    {
        $this->setMethod("put");
        $this->execute();
        return $this;
    }

    public function delete(): Http
    {
        $this->setMethod("delete");
        $this->execute();
        return $this;
    }

    /**
     * @return array<string, mixed>
    */
    public function getResponse(): array
    {
        return json_decode($this->response, true);
    }

    public function print(): string
    {
        $headers = (string) json_encode($this->headers);
        $data = (string) json_encode($this->data);
        return "[{$this->method}] {$this->baseUrl}\n-h {$headers}\n-d {$data}";
    }

    private function setMethod(string $method): void
    {
        if (!in_array($method, ["get", "post", "put", "delete"])) {
            throw new Exception("O método informado para a requisição não é válido.");
        }

        $this->method = $method;
    }

    private function execute(): void
    {
        $ch = curl_init();
        if (empty($this->baseUrl)) {
            throw new Exception("A requisição não pode ser completada.");
        }

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 14);

        switch ($this->method) {
            case "get":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
            case "post":
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($this->data)) {
                    $json = json_encode($this->data);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, ($json !== false ? $json : ''));  //Post Fields
                }
                break;
            case "put":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!empty($this->data)) {
                    $json = json_encode($this->data);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, ($json !== false ? $json : ''));  //Post Fields
                }
                break;
            case "delete":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        $server_output = curl_exec($ch);

        if (!$server_output) {
            $errorCode = curl_errno($ch);
            $err = curl_error($ch);
            throw new Exception("{$err} - Code: {$errorCode}");
        }

        curl_close($ch);
        $this->response = (string) $server_output;
    }
}
