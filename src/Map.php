<?php

namespace Prob\Router;

use Prob\Handler\ProcFactory;
use Prob\Handler\ProcInterface;

class Map
{
    private $handlers = [];
    private $namespace = '';

    public function setNamespace($namespace = '\\')
    {
        $this->namespace = $namespace;
    }

    public function get($path, $handler)
    {
        $this->handlers[] = $this->buildMethodHandler('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->handlers[] = $this->buildMethodHandler('POST', $path, $handler);
    }

    private function buildMethodHandler($method, $path, $handler)
    {
        $methodHandler = new MethodHandler();
        $methodHandler->setMethod($method);
        $methodHandler->setUrlPattern($path);
        $methodHandler->setHandlerProc(ProcFactory::getProc($handler, $this->namespace));

        return $methodHandler;
    }

    public function getHandlerByMethod($method)
    {
        $handlers = [];

        /** @var $handler MethodHandler */
        foreach ($this->handlers as $handler) {
            if ($handler->getMethod() === $method) {
                $handlers[] = $handler;
            }
        }

        return $handlers;
    }
}
