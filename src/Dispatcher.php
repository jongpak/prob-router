<?php

namespace Prob\Router;

use Prob\Rewrite\Request;
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
     * @return mixed return value of dispatch handler
     * @throws RoutePathNotFound
     */
    public function dispatch(Request $request)
    {
        $match = $this->matcher->match($request);

        if ($match === false)
            throw new RoutePathNotFound('Route path not found: ' . $request->path());

        return $match['handler']->exec($match['urlNameMatch']);
    }
}