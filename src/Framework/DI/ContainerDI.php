<?php

namespace App\Framework\DI;

use App\Framework\DI\Exception\FailReadServicesConfigException;
use App\Framework\DI\Exception\ServiceExistsException;
use App\Framework\DI\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

class ContainerDI implements ContainerInterface
{
    private array $services = [];
    private array $instances = [];

    private string $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function get(string $id): mixed
    {
        if ($this->hasInstance($id)) {
            return $this->instances[$id];
        }
        if ($this->has($id)) {
            $instance = $this->services[$id]($this);
            $this->instances[$id] = $instance;
            return $instance;
        }
        throw new ServiceNotFoundException("Зависимость с именем $id не найдена");
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @throws ServiceExistsException
     */
    public function register(string $name, callable $factoryCallback): void
    {
        if ($this->has($name)) {
            throw new ServiceExistsException("Зависимость с именем $name уже существует");
        }
        $this->services[$name] = $factoryCallback;
    }

    /**
     * @throws ServiceExistsException|FailReadServicesConfigException
     */
    public function compile(string $filename = 'services.php'): void
    {
        try {
            $services = require $this->configPath . $filename;

            foreach ($services as $name => $callable) {
                $this->register($name, $callable);
            }
        } catch (\Error) {
            throw new FailReadServicesConfigException("Не найден конфигурационный файл " . $this->configPath . $filename);
        }
    }

    private function hasInstance($name): bool
    {
        return isset($this->instances[$name]);
    }
}