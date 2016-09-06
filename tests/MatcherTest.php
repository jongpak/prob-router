<?php

namespace Prob\Router;

use PHPUnit\Framework\TestCase;
use Prob\Handler\ProcFactory;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

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
        $request = (new Request())
            ->withUri(new Uri('http://test.com/'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => []
        ], $this->matcher->match($request));
    }

    public function testMatchOneDeep()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/some'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/some',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => []
        ], $this->matcher->match($request));
    }

    public function testMatchTwoDeep()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/some/other'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/some/other',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => []
        ], $this->matcher->match($request));
    }

    public function testMatchNameHolderOneDeep()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/free'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/{board:string}',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => [
                'board' => 'free'
            ]
        ], $this->matcher->match($request));
    }

    public function testMatchNameHolderTwoDeep()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/free/5'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/{board}/{post:int}',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => [
                'board' => 'free',
                'post' => '5'
            ]
        ], $this->matcher->match($request));
    }

    public function testMatchNameHolderTwoDeepOneStatic()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/free/5/edit'))
            ->withMethod('GET')
            ;

        $this->assertEquals([
            'urlPattern' => '/{board:string}/{post:int}/edit',
            'handler' => ProcFactory::getProc('test', 'Prob\Router'),
            'urlNameMatching' => [
                'board' => 'free',
                'post' => '5'
            ]
        ], $this->matcher->match($request));
    }

    public function testNoMatch()
    {
        $request = (new Request())
            ->withUri(new Uri('http://test.com/some/no_match/'))
            ->withMethod('GET')
            ;

        print_r($this->matcher->match($request));
        $this->assertEquals(false, $this->matcher->match($request));
    }
}

function test()
{
    /*...*/
}
