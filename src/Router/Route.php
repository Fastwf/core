<?php

namespace Fastwf\Core\Router;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Core\Engine\Context;
use Fastwf\Core\Router\BaseRoute;
use Fastwf\Api\Utils\AsyncProperty;
use Fastwf\Core\Components\RequestHandler;
use Fastwf\Core\Router\Parser\RouteParser;
use Fastwf\Core\Router\Parser\SpecificationRouteParser;

/**
 * Class that help to perform route match and parameter extraction.
 */
class Route extends BaseRoute {

    protected $methods;
    protected $handler;

    /**
     * Route constructor
     * 
     * Parameters:
     * - "methods": [required] the array of authorized methods
     * - "handler": [required] the request handler attached to the route
     * 
     * {@see BaseRoute} for other parameters
     */
    public function __construct($params) {
        parent::__construct($params);

        $this->handler = new AsyncProperty(ArrayUtil::get($params, "handler"));
        // transform the methods
        $this->methods = [];
        foreach (ArrayUtil::get($params, "methods") as $method) {
            $this->methods[] = \strtoupper($method);
        }
    }

    public function match($path, $method) {
        if (!\in_array($method, $this->methods)) {
            return null;
        } else {
            return $this->matchPath($path);
        }
    }

    /**
     * Verify that the path match this route and return null or matching infos.
     *
     * @param string $path the path of the request.
     * @return array|null the matching information.
     */
    public function matchPath($path) {
        $parameters = [];
        
        $routeParser = new SpecificationRouteParser($this->path);
        $pathParser = new RouteParser($path);

        $routeParser->rewind();
        $pathParser->rewind();
        // Iteration over the full path (spec and path using same progression)
        while ($routeParser->valid() && $pathParser->valid()) {
            $segment = $routeParser->current();

            if (!$segment->match($pathParser->current())) {
                // No match -> return null
                return null;
            }
            
            if ($segment->isWildcard()) {
                // Full match the next part -> return extracted parameters
                return ["matchers" => [$this], "parameters" => $parameters];
            } else if ($segment->isParameter()) {
                // Collect parameter using the $this->name as parameter namespace
                $parameters[self::getParameterName($this->name, $segment->getName())] = $segment->getParameter();
            }

            $routeParser->next();
            $pathParser->next();
        }

        // When the both parser are invalid, the route specification and the path match
        //  return parameters else null
        return (!$routeParser->valid() || $routeParser->current()->isPath()) && !$pathParser->valid()
            ? ["matchers" => [$this], "parameters" => $parameters]
            : null;
    }

    /**
     * Get the handler associated to the route.
     *
     * @param Context $context the engine context
     * @return RequestHandler the request handler
     */
    public function getHandler($context) {
        return $this->handler->get($context);
    }

}
