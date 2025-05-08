<?php


namespace App\Framework\Http;

use App\Framework\Exception\ValidationException;
use App\Framework\Http\Exception\ValidationDataException;

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

    /**
     * @throws ValidationException
     */
    public function validateData(array $requiredField): void
    {
        foreach ($requiredField as $field) {
            if (!isset($this->data[$field])) {
                throw new ValidationDataException("Отсутствует обязательное поле $field");
            }
        }
    }
}
