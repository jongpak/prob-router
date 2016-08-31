<?php

namespace Prob\Router;

use Prob\Handler\ProcFactory;
use Prob\Handler\ProcInterface;

class Map
{
    /**
     * array['GET'|'POST']
     *          [index]
     *              ['urlPattern']  string          a pattern of url (ex.) /{board}/{post}
     *              ['handler']     ProcInterface   a handler of urlPattern
     * @var array
     */
    private $handlers = [
        'GET' => [],
        'POST' => [],
    ];

    private $namespace = '';

    public function setNamespace($namespace = '\\')
    {
        $this->namespace = $namespace;
    }

    public function get($path, $handler)
    {
        $this->addHandler('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addHandler('POST', $path, $handler);
    }

    private function addHandler($method, $path, $handler)
    {
        $this->handlers[$method][] = [
            'urlPattern' => $path,
            'handler' => ProcFactory::getProc($handler, $this->namespace)
        ];
    }

    /**
     * Return array of handlers
     *
     * return value:
     * array[index]
     *          ['urlPattern']  string          a pattern of url (ex.) /{board}/{post}
     *          ['handler']     ProcInterface   a handler of urlPattern
     *
     * @return array
     */
    public function getHandlers($handler)
    {
        return $this->handlers[$handler];
    }
}
