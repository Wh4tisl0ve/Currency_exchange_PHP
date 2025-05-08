<?php

namespace Tests\Framework\Container;

use App\Framework\Container\Container;
use App\Framework\Container\Exception\ServiceExistsException;
use App\Framework\Container\Exception\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public Container $containerDI;

    protected function setUp(): void
    {
        $this->containerDI = new Container('test');

        $this->containerDI->register('testService', function (Container $container) {
            return 'test';
        });
    }

    public function testSuccessRegisterService()
    {
        $this->containerDI->register('test', function (Container $container) {
            return 'test';
        });

        $this->assertTrue(true);
    }

    public function testRegisterExistsService()
    {
        $this->containerDI->register('test', function (Container $container) {
            return 'test';
        });

        $this->expectException(ServiceExistsException::class);

        $this->containerDI->register('test', function (Container $container) {
            return 'test';
        });

        $this->assertTrue(true);
    }

    public function testSuccessGetService()
    {
        $service = $this->containerDI->get('testService');

        $this->assertIsString($service);
        $this->assertTrue(true);
    }

    public function testSuccessGetNotExistsService()
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->containerDI->get('testService1');
        $this->assertTrue(true);
    }
}