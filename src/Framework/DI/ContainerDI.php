<?php

namespace App\Framework\DI;

use App\Framework\DI\Exception\FailReadServicesConfigException;
use App\Framework\DI\Exception\ServiceExistsException;
use App\Framework\DI\Exception\ServiceNotFoundException;


class ContainerDI
{
    private array $services = [];
    private array $instances = [];

    private string $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function get(string $name): mixed
    {
        if ($this->hasInstance($name)) {
            return $this->instances[$name];
        }
        if ($this->hasService($name)) {
            $instance = $this->services[$name]($this);
            $this->instances[$name] = $instance;
            return $instance;
        }
        throw new ServiceNotFoundException("Зависимость с именем $name не найдена");
    }

    /**
     * @param string $name
     * @param callable $factoryCallback
     * @return void
     * @throws ServiceExistsException
     */
    public function register(string $name, callable $factoryCallback): void
    {
        if ($this->hasService($name)) {
            throw new ServiceExistsException("Зависимость с именем $name уже существует");
        }
        $this->services[$name] = $factoryCallback;
    }

    /**
     * @param string $filename
     * @return void
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

    private function hasService($name): bool
    {
        return isset($this->services[$name]);
    }

    private function hasInstance($name): bool
    {
        return isset($this->instances[$name]);
    }
}