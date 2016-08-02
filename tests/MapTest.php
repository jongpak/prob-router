<?php

namespace jongpak\Router;

use PHPUnit\Framework\TestCase;
use jongpak\Handler\Proc;

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
        $this->assertEquals($this->map->method('GET'), [
            [
                'urlPattern' => '/',
                'handler' => new Proc(function () {
                    echo 'getOk';
                })
            ],
            [
                'urlPattern' => '/some',
                'handler' => new Proc(function () {
                    echo 'getOkSome';
                })
            ]
        ]);
    }

    public function testPostMethod()
    {
        $this->assertEquals($this->map->method('POST'), [
            [
                'urlPattern' => '/',
                'handler' => new Proc(function () {
                    echo 'postOk';
                })
            ],
            [
                'urlPattern' => '/some',
                'handler' => new Proc(function () {
                    echo 'postOkSome';
                })
            ]
        ]);
    }
}
