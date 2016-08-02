<?php

namespace Prob\Router;

use Prob\Rewrite\Request;
use Prob\Handler\Proc;
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
     * @param Request $req
     * @return array|bool
     */
    public function match(Request $req)
    {
        $method = $this->map->method($req->method());
        $proc = null;

        foreach ($method as $row) {
            $matcher = new UrlMatcher($row['urlPattern']);
            $result = $matcher->match($req->path());

            $matchResolve = $row;

            if ($result !== false) {
                $matchResolve['urlNameMatch'] = $result;
                return $matchResolve;
            }
        }

        return false;
    }
}
