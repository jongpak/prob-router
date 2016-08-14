<?php

namespace Prob\Router;

use Prob\Rewrite\Request;
use Prob\Handler\ParameterMap;
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
     * @param Request $request
     * @param ParameterMap $map
     * @return mixed return value of dispatch handler
     * @throws RoutePathNotFound
     */
    public function dispatch(Request $request, ParameterMap $map = null)
    {
        $match = $this->matcher->match($request);

        if ($match === false) {
            throw new RoutePathNotFound('Route path not found: ' . $request->getPath());
        }

        if ($map === null) {
            return $match['handler']->exec($match['urlNameMatching']);
        } else {
            return $match['handler']->execWithParameterMap($map);
        }
    }
}
