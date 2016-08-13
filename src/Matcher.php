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
        $handlers = $this->map->getHandlers($req->getMethod());

        foreach ($handlers as $row) {
            $matcher = new UrlMatcher($row['urlPattern']);
            $matchResult = $matcher->match($req->getPath());

            if ($matchResult !== false) {
                $row['urlNameMatching'] = $matchResult;
                return $row;
            }
        }

        return false;
    }
}
