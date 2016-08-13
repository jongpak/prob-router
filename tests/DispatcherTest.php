<?php

namespace Prob\Router;

use PHPUnit\Framework\TestCase;
use Prob\Rewrite\Request;
use Prob\Handler\ParameterMap;
use Prob\Router\Exception\RoutePathNotFound;

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

        $map->get('/parameterMap/{arg1}/{arg2}', function(ParameterMapTest $t, $arg1, $arg2) {
            return $arg1 . $arg2 . $t->get();
        });

        $map->get('/{board:string}', function ($req) {
            return $req;
        });

        $this->dispatcher = new Dispatcher($map);
    }

    public function testDispatchHandler()
    {
        $_SERVER['PATH_INFO'] = '/some';
        $this->assertEquals('ok', $this->dispatcher->dispatch(new Request()));
    }

    public function testDispatchHandlerWithName()
    {
        $_SERVER['PATH_INFO'] = '/other';
        $this->assertEquals(['board' => 'other'], $this->dispatcher->dispatch(new Request()));
    }

    public function testDispatchHandlerWithParameterMap()
    {
        $_SERVER['PATH_INFO'] = '/parameterMap/a/b';

        $paramMap = new ParameterMap();
        $paramMap->bindByName('arg1', 'a');
        $paramMap->bindByName('arg2', 'b');
        $paramMap->bindByNameWithType(ParameterMapTest::class, 't', new ParameterMapTest());

        $this->assertEquals('abok!!!', $this->dispatcher->dispatch(new Request(), $paramMap));
    }

    public function testNoRouteException()
    {
        $this->expectException(RoutePathNotFound::class);
        $this->dispatcher->dispatch(new Request());
    }
}

class ParameterMapTest
{
    private $var = 'ok!!!';

    public function get()
    {
        return $this->var;
    }
}
