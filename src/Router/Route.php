<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Utils\ArrayUtil;
use Fastwf\Core\Router\BaseRoute;
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
     * {@inheritDoc}
     * 
     * Parameters:
     * - "methods": [required] the array of authorized methods
     * - "handler": [required] the request handler attached to the route
     */
    public function __construct($params) {
        parent::__construct($params);

        $this->handler = ArrayUtil::get($params, "handler");
        // transform the methods
        $this->methods = [];
        foreach (ArrayUtil::get($params, "methods") as $method) {
            $this->methods[] = \strtoupper($method);
        }
    }

    public function match($path, $method) {
        if (!\in_array($method, $this->methods)) {
            return null;
        }

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
                $parameters["{$this->name}/{$segment->getName()}"] = $segment->getParameter();
            }

            $routeParser->next();
            $pathParser->next();
        }

        // When the both parser are invalid, the route specification and the path match
        //  return parameters else null
        return !$routeParser->valid() && !$pathParser->valid()
            ? ["matchers" => [$this], "parameters" => $parameters]
            : null;
    }

}
