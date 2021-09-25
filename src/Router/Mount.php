<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Utils\ArrayUtil;
use Fastwf\Core\Utils\AsyncProperty;
use Fastwf\Core\Router\BaseRoute;
use Fastwf\Core\Router\MountException;
use Fastwf\Core\Router\Parser\RouteParser;
use Fastwf\Core\Router\Parser\SpecificationRouteParser;

/**
 * The mount component allows to attach sub routes by loading them synchronously 
 * or asynchronously.
 */
class Mount extends BaseRoute {

    protected $routes;

    /**
     * Mount constructor.
     * 
     * {@inheritDoc}
     *
     * Parameters:
     * - "routes": [required] the list of route/mount to expose behind this mount point
     */
    public function __construct($params) {
        parent::__construct($params);

        $this->routes = new AsyncProperty(ArrayUtil::get($params, "routes"));
    }

    /**
     * Verify that the path match the start of the mount path specification.
     *
     * @param Fastwf\Core\Router\Parser\SpecificationRouteParser $mountParser the segment specification
     * @param Fastwf\Core\Router\Parser\RouteParser $pathParser the route parser
     * @param array $parameters the array that contains the parameters
     * @param boolean $stop the boolean to set when the result must return before
     * @return array|null null or match
     */
    private function matchPath($mountParser, $pathParser, &$parameters, &$stop) {
        $mountParser->rewind();
        $pathParser->rewind();

        // In case of path equals to ''
        $stop = !$pathParser->valid();

        $mountIsValid = $mountParser->valid();
        while ($mountIsValid && $pathParser->valid()) {
            $segment = $mountParser->current();

            if (!$segment->match($pathParser->current())) {
                // The mount point don't match the current path -> return null
                $stop = true;

                return null;
            }

            if ($segment->isWildcard() || $segment->isPath()) {
                // When the segment is wildcard or path, the next path is full match
                //  Because it's not possible to check the end of the path, path and wildcard are not allowed
                throw new MountException("Wildcard '**' or path '{path:parameterName}' are not allowed for mount path specification");
            } else if ($segment->isParameter()) {
                $parameters[self::getParameterName($this->name, $segment->getName())] = $segment->getParameter();
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

        return null;
    }

    // Implementation

    public function match($path, $method) {
        $parameters = [];
        
        $mountParser = new SpecificationRouteParser($this->path);
        $pathParser = new RouteParser($path);
    
        if ($this->path !== '') {
            $stop = false;
            $result = $this->matchPath($mountParser, $pathParser, $parameters, $stop);

            if ($stop) {
                return $result;
            }
        }

        // Now, all mount path segments match the current path,
        //  delegate the match method to child routes
        $nextPath = $pathParser->getNextPath();

        foreach ($this->routes->get() as $route) {
            $result = $route->match($nextPath, $method);

            // If the result !== null -> it's a match, return it
            if ($result !== null) {
                // Merge the parameter with sub route parameters and return it
                return [
                    "matchers" => \array_merge([$this], $result["matchers"]),
                    "parameters" => \array_replace($parameters, $result["parameters"]),
                ];
            }
        }

        // Sub routes never match the current route -> return null
        return null;
    }

}
