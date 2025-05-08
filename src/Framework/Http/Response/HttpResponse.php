<?php

namespace App\Framework\Http\Response;

class HttpResponse
{
    private int $statusCode;

    private string $content;

    public function __construct(string $content, int $statusCode)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header("Cache-Control: no-cache");
        header("X-XSS-Protection: 1; mode=block");

        echo $this->content;

        fastcgi_finish_request();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}