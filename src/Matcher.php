<?php

namespace Prob\Router;

use Psr\Http\Message\RequestInterface;
use Prob\Url\Matcher as UrlMatcher;

class Matcher
{
    /**
     * @var Map
     */
    private $map;

    /**
     * Matcher constructor.
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @param RequestInterface $request
     * @return array|bool
     */
    public function match(RequestInterface $request)
    {
        $handlers = $this->map->getHandlers($request->getMethod());

        foreach ($handlers as $row) {
            $matcher = new UrlMatcher($row['urlPattern']);
            $urlMatching = $matcher->match($request->getUri()->getPath());

            if ($urlMatching !== false) {
                $row['urlNameMatching'] = $urlMatching;
                return $row;
            }
        }

        return false;
    }
}
