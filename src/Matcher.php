<?php

namespace Prob\Router;

use Psr\Http\Message\RequestInterface;
use Prob\Url\Matcher as UrlMatcher;
use Prob\Router\MethodHandler;

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
        $handlers = $this->map->getHandlerByMethod($request->getMethod());

        /** @var $handler MethodHandler */
        foreach ($handlers as $handler) {
            $matcher = new UrlMatcher();
            $matcher->setUrlFormat($handler->getUrlPattern());

            $path = $request->getUri()->getPath();

            if ($matcher->isMatch($path)) {
                return [
                    'urlNameMatching' => $matcher->getMatchedUrlFormat($path),
                    'handler' => $handler->getHandlerProc(),
                    'urlPattern' => $handler->getUrlPattern()
                ];
            }
        }

        return false;
    }
}
