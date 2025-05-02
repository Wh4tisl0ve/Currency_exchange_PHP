<?php

namespace Tests\Framework\DI;

use App\Framework\DI\ContainerDI;
use App\Framework\DI\Exception\ServiceExistsException;
use App\Framework\DI\Exception\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerDITest extends TestCase
{
    public ContainerDI $containerDI;

    protected function setUp(): void
    {
        $this->containerDI = new ContainerDI('test');

        $this->containerDI->register('testService', function (ContainerDI $container) {
            return 'test';
        });
    }

    public function testSuccessRegisterService()
    {
        $this->containerDI->register('test', function (ContainerDI $container) {
            return 'test';
        });

        $this->assertTrue(true);
    }

    public function testRegisterExistsService()
    {
        $this->containerDI->register('test', function (ContainerDI $container) {
            return 'test';
        });

        $this->expectException(ServiceExistsException::class);

        $this->containerDI->register('test', function (ContainerDI $container) {
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