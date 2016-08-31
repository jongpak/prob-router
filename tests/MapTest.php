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
        $this->assertEquals([
            [
                'urlPattern' => '/',
                'handler' => ProcFactory::getProc(function () {
                    echo 'getOk';
                })
            ],
            [
                'urlPattern' => '/some',
                'handler' => ProcFactory::getProc(function () {
                    echo 'getOkSome';
                })
            ]
        ], $this->map->getHandlers('GET'));
    }

    public function testPostMethod()
    {
        $this->assertEquals([
            [
                'urlPattern' => '/',
                'handler' => ProcFactory::getProc(function () {
                    echo 'postOk';
                })
            ],
            [
                'urlPattern' => '/some',
                'handler' => ProcFactory::getProc(function () {
                    echo 'postOkSome';
                })
            ]
        ], $this->map->getHandlers('POST'));
    }
}
