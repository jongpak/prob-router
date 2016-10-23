<?php

namespace Prob\Router;

use Psr\Http\Message\RequestInterface;
use Prob\Handler\ParameterMap;
use Prob\Handler\ProcInterface;
use Prob\Handler\Proc\MethodProc;
use Prob\Router\Exception\RoutePathNotFound;

class Dispatcher
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * Dispatcher constructor.
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->matcher = new Matcher($map);
    }

    /**
     * If $map argument is null,
     * Dispatcher call a handler with a argument about array of url matching result.
     * otherwise,
     * Dispatcher call a handler with matcing arguments by ParameterMap
     *
     * @param RequestInterface $request
     * @param ParameterMap $map
     * @return mixed return value of dispatch handler
     * @throws RoutePathNotFound
     */
    public function dispatch(RequestInterface $request, ParameterMap $map = null)
    {
        $this->validateRoutePath($request);

        /**
         * Maching result of reqeust handler
         * @var array|bool
         */
        $requestMatching = $this->matcher->match($request);

        if ($map === null) {
            if ($requestMatching['handler'] instanceof MethodProc) {
                $requestMatching['handler']->execConstructor();
            }
            return $requestMatching['handler']->exec($requestMatching['urlNameMatching']);
        }

        if ($requestMatching['handler'] instanceof MethodProc) {
            $requestMatching['handler']->execConstructorWithParameterMap($map);
        }
        return $requestMatching['handler']->execWithParameterMap($map);
    }

    private function validateRoutePath(RequestInterface $request)
    {
        if ($this->matcher->match($request) === false) {
            throw new RoutePathNotFound(
                sprintf('Route path not found: %s (%s)',
                            $request->getUri()->getPath(),
                            $request->getMethod()
                )
            );
        }
    }
}
