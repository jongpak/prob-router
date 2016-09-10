<?php

namespace Prob\Router;

use PHPUnit\Framework\TestCase;
use Prob\Handler\ProcFactory;

class MapTest extends TestCase
{
    /**
     * @var Map
     */
    private $map;

    public function setUp()
    {
        $map = new Map();

        $map->get('/', function () {
            echo 'getOk';
        });
        $map->get('/some', function () {
            echo 'getOkSome';
        });

        $map->post('/', function () {
            echo 'postOk';
        });
        $map->post('/some', function () {
            echo 'postOkSome';
        });

        $this->map = $map;
    }

    public function testGetMethod()
    {
        $this->assertEquals('/', $this->map->getHandlerByMethod('GET')[0]->getUrlPattern());
        $this->assertEquals('/some', $this->map->getHandlerByMethod('GET')[1]->getUrlPattern());

        $this->expectOutputString('getOkgetOkSome');
        $this->map->getHandlerByMethod('GET')[0]->getHandlerProc()->exec();
        $this->map->getHandlerByMethod('GET')[1]->getHandlerProc()->exec();
    }

    public function testPostMethod()
    {
        $this->assertEquals('/', $this->map->getHandlerByMethod('POST')[0]->getUrlPattern());
        $this->assertEquals('/some', $this->map->getHandlerByMethod('POST')[1]->getUrlPattern());

        $this->expectOutputString('postOkpostOkSome');
        $this->map->getHandlerByMethod('POST')[0]->getHandlerProc()->exec();
        $this->map->getHandlerByMethod('POST')[1]->getHandlerProc()->exec();
    }
}
