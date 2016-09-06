<?php

namespace Prob\Router;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;
use Prob\Handler\ParameterMap;
use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\TypedAndNamed;
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

        $map->get('/parameterMap/{arg1}/{arg2}', function (ParameterMapTest $t, $arg1, $arg2) {
            return $arg1 . $arg2 . $t->get();
        });

        $map->get('/{board:string}', function ($req) {
            return $req;
        });

        $this->dispatcher = new Dispatcher($map);
    }

    public function testDispatchHandler()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/some'))
            ->withMethod('GET')
            ;
        $this->assertEquals('ok', $this->dispatcher->dispatch($request));
    }

    public function testDispatchHandlerWithName()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/boardName'))
            ->withMethod('GET')
            ;
        $this->assertEquals(['board' => 'boardName'], $this->dispatcher->dispatch($request));
    }

    public function testDispatchHandlerWithParameterMap()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/parameterMap/a/b'))
            ->withMethod('GET')
            ;

        $paramMap = new ParameterMap();
        $paramMap->bindBy(new Named('arg1'), 'a');
        $paramMap->bindBy(new Named('arg2'), 'b');
        $paramMap->bindBy(new TypedAndNamed(ParameterMapTest::class, 't'), new ParameterMapTest());

        $this->assertEquals('abok!!!', $this->dispatcher->dispatch($request, $paramMap));
    }

    public function testNoRouteException()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/'))
            ->withMethod('GET')
            ;

        $this->expectException(RoutePathNotFound::class);
        $this->dispatcher->dispatch($request);
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
