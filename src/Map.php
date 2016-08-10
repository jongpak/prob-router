<?php

namespace Prob\Router;

use Prob\Handler\Proc;

class Map
{
    private $handlers = [
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
        $this->handlers['GET'][] = [
            'urlPattern' => $path,
            'handler' => new Proc($handler, $this->namespace)
        ];
    }

    public function post($path, $handler)
    {
        $this->handlers['POST'][] = [
            'urlPattern' => $path,
            'handler' => new Proc($handler, $this->namespace)
        ];
    }

    public function getHandlers($handler)
    {
        return $this->handlers[$handler];
    }
}
