<?php


namespace App\Framework\Http;

class HttpRequest
{
    private string $uri;
    private string $method;
    private array $data;

    public function __construct(array $data, string $uri, string $method)
    {
        $this->data = $data;
        $this->uri = $uri;
        $this->method = $method;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
