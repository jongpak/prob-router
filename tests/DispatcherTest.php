<?php

namespace jongpak\Router;

use jongpak\Rewrite\Request;
use jongpak\Router\Exception\RoutePathNotFound;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $map = new Map();
        $map->get('/some', function () {
            return 'ok';
        });

        $map->get('/{board:string}', function ($req) {
            return $req;
        });

        $this->dispatcher = new Dispatcher($map);
    }

    public function testDispatchHandler()
    {
        $_SERVER['PATH_INFO'] = '/some';
        $this->assertEquals($this->dispatcher->dispatch(new Request()), 'ok');
    }

    public function testDispatchHandlerWithName()
    {
        $_SERVER['PATH_INFO'] = '/other';
        $this->assertEquals($this->dispatcher->dispatch(new Request()), [
            'board' => 'other'
        ]);
    }

    public function testNoRouteException()
    {
        $this->expectException(RoutePathNotFound::class);
        $this->dispatcher->dispatch(new Request());
    }
}
