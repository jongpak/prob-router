<?php

namespace Prob\Router;

use Prob\Handler\Proc;

class Map
{
    private $method = [
        'GET' => [],
        'POST' => [],
    ];

    private $namespace = '';

    public function setNamespace($ns = '\\')
    {
        $this->namespace = $ns;
    }

    public function get($path, $handler)
    {
        $this->method['GET'][] = [
            'urlPattern' => $path,
            'handler' => new Proc($handler, $this->namespace)
        ];
    }

    public function post($path, $handler)
    {
        $this->method['POST'][] = [
            'urlPattern' => $path,
            'handler' => new Proc($handler, $this->namespace)
        ];
    }

    public function method($method)
    {
        return $this->method[$method];
    }
}