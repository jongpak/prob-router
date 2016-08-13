<?php

namespace Prob\Router;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc;
use Prob\Rewrite\Request;

class MatcherTest extends TestCase
{
    /**
     * @var Matcher
     */
    private $matcher;

    public function setUp()
    {
        $map = new Map();
        $map->setNamespace('Prob\\Router');
        $map->get('/', 'test');
        $map->get('/some', 'test');
        $map->get('/some/other', 'test');
        $map->get('/{board:string}', 'test');
        $map->get('/{board}/{post:int}', 'test');
        $map->get('/{board:string}/{post:int}/edit', 'test');

        $this->matcher = new Matcher($map);
    }

    public function testMatchRoot()
    {
        $_SERVER['PATH_INFO'] = '/';

        $this->assertEquals([
            'urlPattern' => '/',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ], $this->matcher->match(new Request()));
    }

    public function testMatchOneDeep()
    {
        $_SERVER['PATH_INFO'] = '/some';

        $this->assertEquals([
            'urlPattern' => '/some',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ], $this->matcher->match(new Request()));
    }

    public function testMatchTwoDeep()
    {
        $_SERVER['PATH_INFO'] = '/some/other';

        $this->assertEquals([
            'urlPattern' => '/some/other',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ], $this->matcher->match(new Request()));
    }

    public function testMatchNameHolderOneDeep()
    {
        $_SERVER['PATH_INFO'] = '/free';

        $this->assertEquals([
            'urlPattern' => '/{board:string}',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free'
            ]
        ], $this->matcher->match(new Request()));
    }

    public function testMatchNameHolderTwoDeep()
    {
        $_SERVER['PATH_INFO'] = '/free/5';

        $this->assertEquals([
            'urlPattern' => '/{board}/{post:int}',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free',
                'post' => '5'
            ]
        ], $this->matcher->match(new Request()));
    }

    public function testMatchNameHolderTwoDeepOneStatic()
    {
        $_SERVER['PATH_INFO'] = '/free/5/edit';

        $this->assertEquals([
            'urlPattern' => '/{board:string}/{post:int}/edit',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free',
                'post' => '5'
            ]
        ], $this->matcher->match(new Request()));
    }

    public function testNoMatch()
    {
        $_SERVER['PATH_INFO'] = '/no_match/';

        $this->assertEquals(false, $this->matcher->match(new Request()));
    }


}

function test()
{
    /*...*/
}
