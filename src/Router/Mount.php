<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Router\Parser\RouteParser;
use Fastwf\Core\Router\Parser\SpecificationRouteParser;

/**
 * The mount component allows to attach sub routes by loading them synchronously 
 * or asynchronously.
 */
class Mount extends BaseRoute {

    protected $routes = null;
    protected $asyncRoutes = null;

    public function __construct($path, $routes, $namespace) {
        parent::__construct($path, $namespace);

        if (is_callable($routes)) {
            $this->asyncRoutes = &$routes;
        } else {
            $this->routes = $routes;
        }
    }

    // Implementation

    public function match($path, $method) {
        $parameters = [];
        
        $mountParser = new SpecificationRouteParser($this->path);
        $pathParser = new RouteParser($path);

        $mountParser->rewind();
        $pathParser->rewind();

        $mountIsValid = $mountParser->valid();
        while ($mountIsValid && $pathParser->valid()) {
            $segment = $mountParser->current();

            if (!$segment->match($pathParser->current())) {
                // The mount point don't match the current path -> return null
                return null;
            }

            if ($segment->isWildcard()) {
                // When the segment is wildcard, the next path is full match
                return $segment;
            } else if ($segment->isParameter()) {
                $parameters["{$this->name}/{$segment->getName()}"] = $segment->getParameter();
            }

            // To next segment
            $mountParser->next();
            $mountIsValid = $mountParser->valid();
            // Prevent to go to the next segment if the mount parser is invalid
            //  otherwise the nextPath become invalid
            if ($mountIsValid) {
                $pathParser->next();
            }
        }

        // Now, all mount path segments match the current path,
        //  delegate the match method to child routes
        $nextPath = $pathParser->getNextPath();

        foreach ($this->getRoutes() as $route) {
            $result = $route->match($nextPath, $method);

            // If the result !== null -> it's a match, return it
            if ($result !== null) {
                // Merge the parameter with sub route parameters and return it
                return \array_replace($parameters, $result);
            }
        }

        // Sub routes never match the current route -> return null
        return null;
    }

    /// Private methods

    private function getRoutes() {
        if ($this->routes === null) {
            $this->routes = \call_user_func($this->asyncRoutes);
        }

        return $this->routes;
    }

}
