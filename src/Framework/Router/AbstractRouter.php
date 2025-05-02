<?php

namespace App\Framework\Router;


abstract class AbstractRouter
{
    protected array $routes = [];

    protected string $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    public abstract function get(string $nameRoute);

    public abstract function register(array $handler, string $nameRoute);
}
