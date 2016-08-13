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
     * @param Request $request
     * @param ParameterMap $map
     * @return mixed return value of dispatch handler
     * @throws RoutePathNotFound
     */
    public function dispatch(Request $request, ParameterMap $map = null)
    {
        $match = $this->matcher->match($request);

        if ($match === false)
            throw new RoutePathNotFound('Route path not found: ' . $request->getPath());

        if($map === null)
            return $match['handler']->exec($match['urlNameMatch']);
        else
            return $match['handler']->execWithParameterMap($map);
    }
}
