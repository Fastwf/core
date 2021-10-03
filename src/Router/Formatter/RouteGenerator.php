<?php

namespace Fastwf\Core\Router\Formatter;

use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Route;
use Fastwf\Core\Utils\StringUtil;
use Fastwf\Core\Exceptions\KeyError;
use Fastwf\Core\Router\Formatter\PathFormatter;
use Fastwf\Core\Router\Exception\FormatException;
use Fastwf\Core\Router\Formatter\PartialPathFormatter;

/**
 * Helper class for formatting routes.
 * 
 * The Generator must be used as single instance to benefit from the search route logic.
 * 
 * The algorithm search the route and store pathFormatters discovered for named routes. 
 */
class RouteGenerator
{

    protected $router;

    private $routeIterator;
    private $hasNext;
    private $stack;

    private $baseRoutes;

    private $generators = [];

    public function __construct($router)
    {
        $this->router = $router;
    
        $this->routeIterator = $router->getRouteIterator(); 
        $this->routeIterator->rewind();

        $this->hasNext = true;
        $this->stack = [];
        $this->baseRoutes = [new PartialPathFormatter($router)];
    }

    /**
     * Cache the path formatter associated to the given name.
     *
     * @param string $name
     * @param Fastwf\Core\Router\Formatter\PathFormatter $pathFormatter
     */
    private function cachePathFormatter($name, $pathFormatter)
    {
        if (\array_key_exists($name, $this->generators))
        {
            throw new KeyError("A route is already registered for route name '$name'");
        }
        else
        {
            // Cache in the array
            $this->generators[$name] = $pathFormatter;
        }
    }

    /**
     * Allows to search and return the path formatter associated to the given route name.
     *
     * @param string $name the route name (must be unique)
     * @return Fastwf\Core\Router\Formatter\PathFormatter the path formatter to use to generate the route
     */
    private function getPathFormatter($name)
    {
        // Look inside the cache variable
        if (\array_key_exists($name, $this->generators))
        {
            return $this->generators[$name];
        }

        $pathFormatter = null;
        $searching = true;
        while ($this->hasNext && $searching)
        {
            if ($this->routeIterator->valid())
            {
                $item = $this->routeIterator->current();
                $this->routeIterator->next();
        
                if ($item instanceof Route && $item->getName() !== null)
                {
                    // The route can be compared and cached
                    array_push($this->baseRoutes, new PartialPathFormatter($item));

                    $formatter = new PathFormatter($this->baseRoutes);
                    if ($item->getName() === $name)
                    {
                        $searching = false;
                        // Save the copy of the baseRoutes property to return it
                        $pathFormatter = $formatter;
                    }

                    // Cache the baseRoutes items for the $name to accelerate the url generation
                    $this->cachePathFormatter($item->getName(), $formatter);
                    array_pop($this->baseRoutes);
                }
                else if ($item instanceof Mount)
                {
                    // Stack the current iterator and iterate on discovered Mount point
                    array_push($this->stack, $this->routeIterator);
                    array_push($this->baseRoutes, new PartialPathFormatter($item));
        
                    $this->routeIterator = $item->getRouteIterator();
                    $this->routeIterator->rewind();
                }
            }
            else if (!empty($this->stack))
            {
                // There is no more route to iterate but other iterators are stacked, use the parent iterator
                $this->routeIterator = array_pop($this->stack);
                array_pop($this->baseRoutes);
            }
            else
            {
                // There is no more mount point to iterate -> stop loop
                $this->hasNext = false;
            }
        }

        if ($pathFormatter === null)
        {
            // The url is not found, the name not match the route
            throw new FormatException("No path formatter found for route named '$name'");
        }

        return $pathFormatter;
    }

    /**
     * Search and generate the path associated to the route name.
     *
     * @param string $name the name of the route.
     * @param string $parameters the parameters to inject as path parameter
     * @param string $query the query parameter to add to the url.
     * @param string $fragment the fragment to set to the url.
     * @return sring the path url encoded
     * @throws Fastwf\Core\Exceptions\KeyError when more than one route with the same name is discovered
     * @throws Fastwf\Core\Router\Exception\FormatException when formatting operation failed
     */
    public function generate($name, $parameters = null, $query = null, $fragment = null)
    {
        // Build the absolute path
        $fullPath = $this->getPathFormatter($name)->format($parameters);

        // Build the query string part
        if ($query !== null)
        {
            $fullPath .= '?' . http_build_query($query);
        }

        // Append the fragment when it's provided
        if ($fragment !== null)
        {
            $fullPath .= '#' . \urlencode($fragment);
        }

        return $fullPath;
    }

}
