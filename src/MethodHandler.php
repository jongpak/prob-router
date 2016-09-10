<?php

namespace Prob\Router;

use Prob\Handler\ProcInterface;

class MethodHandler
{
    private $method = 'GET';
    private $urlPattern;

    /**
     * @var ProcInterface
     */
    private $handlerProc;

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setUrlPattern($urlPattern)
    {
        $this->urlPattern = $urlPattern;
    }

    public function getUrlPattern()
    {
        return $this->urlPattern;
    }

    public function setHandlerProc(ProcInterface $handlerProc)
    {
        $this->handlerProc = $handlerProc;
    }

    /**
     * @return ProcInterface
     */
    public function getHandlerProc()
    {
        return $this->handlerProc;
    }
}
