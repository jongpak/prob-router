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

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ]);
    }

    public function testMatchOneDeep()
    {
        $_SERVER['PATH_INFO'] = '/some';

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/some',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ]);
    }

    public function testMatchTwoDeep()
    {
        $_SERVER['PATH_INFO'] = '/some/other';

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/some/other',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => []
        ]);
    }

    public function testMatchNameHolderOneDeep()
    {
        $_SERVER['PATH_INFO'] = '/free';

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/{board:string}',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free'
            ]
        ]);
    }

    public function testMatchNameHolderTwoDeep()
    {
        $_SERVER['PATH_INFO'] = '/free/5';

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/{board}/{post:int}',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free',
                'post' => '5'
            ]
        ]);
    }

    public function testMatchNameHolderTwoDeepOneStatic()
    {
        $_SERVER['PATH_INFO'] = '/free/5/edit';

        $this->assertEquals($this->matcher->match(new Request()), [
            'urlPattern' => '/{board:string}/{post:int}/edit',
            'handler' => new Proc('test', 'Prob\Router'),
            'urlNameMatch' => [
                'board' => 'free',
                'post' => '5'
            ]
        ]);
    }

    public function testNoMatch()
    {
        $_SERVER['PATH_INFO'] = '/no_match/';

        $this->assertEquals($this->matcher->match(new Request()), false);
    }


}

function test()
{
    /*...*/
}