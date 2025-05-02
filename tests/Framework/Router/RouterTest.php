<?php

namespace Tests\Framework\Router;

use App\Framework\Router\AbstractRouter;
use App\Framework\Router\Exception\RouteExistsException;
use App\Framework\Router\Exception\RouteNotExistsException;
use App\Framework\Router\HttpRouter;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private AbstractRouter $router;

    public function setUp(): void
    {
        $this->router = new HttpRouter('');
    }

    public function testSuccessRegisterRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');
        $this->assertTrue(true);
    }

    public function testRegisterExistsRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');

        $this->expectException(RouteExistsException::class);

        $this->router->register(['handler', 'get'], 'testName');
        $this->assertTrue(true);
    }

    public function testSuccessGetRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');

        $handler = $this->router->get('testName');

        $expectedResult = ['handler', 'get'];

        $this->assertEquals($expectedResult, $handler);
    }

    public function testGetNotExistsRoute()
    {
        $this->expectException(RouteNotExistsException::class);

        $this->router->get('testName');

        $this->assertTrue(true);
    }
}