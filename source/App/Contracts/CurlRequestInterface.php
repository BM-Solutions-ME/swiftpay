<?php

namespace Source\App\Contracts;

interface CurlRequestInterface
{
    public function setBaseUrl(string $baseUrl): self;

    /**
     * @param array<string, mixed>|null $headers
     * @return self
    */
    public function setHeaders(?array $headers): self;

    /**
     * @param array<string, mixed>|null $data
     * @return self
    */
    public function setData(?array $data): self;
    public function get(): self;

    public function post(): self;

    public function put(): self;

    public function delete(): self;

    public function getResponse(): mixed;

    public function print(): string;
}