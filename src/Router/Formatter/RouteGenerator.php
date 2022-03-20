<?php

namespace Fastwf\Core\Router\Formatter;

use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Route;
use Fastwf\Api\Exceptions\KeyError;
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

    public function __construct($router, $state = null)
    {
        $this->router = $router;
    
        $this->routeIterator = $router->getRouteIterator(); 
        $this->routeIterator->rewind();

        $this->stack = [];
        if ($state === null)
        {
            $this->hasNext = true;
            $this->baseRoutes = [new PartialPathFormatter($router)];
        }
        else
        {
            // Restore from the state in parameter
            $this->hasNext = $state['hasNext'];
            $this->baseRoutes = $state['baseRoutes'];
            $this->generators = $state['generators'];

            // Rebuild the stack
            foreach ($state['stackOffsets'] as $offset)
            {
                // Seek $offset - 1 to stack the item before calling next and restore the state for the parent iterator
                //  According to the algorithm, $offset - 1 >= 0
                $this->routeIterator->seek($offset - 1);

                $iterator = $this->routeIterator
                    ->current()
                    ->getRouteIterator();
                $iterator->rewind();

                \array_push($this->stack, $iterator);

                $this->routeIterator->next();
                $this->routeIterator = $iterator;
            }

            // Finalize by setting the offset using 'routeIteratorOffset' key
            $this->routeIterator->seek($state['routeIteratorOffset']);
        }
    }

    /**
     * Cache the path formatter associated to the given name.
     *
     * @param string $name
     * @param PathFormatter $pathFormatter
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
     * Handle the current item of the route iterator and try to find the path formatter.
     *
     * @param string $name the route name
     * @param PathFormatter $pathFormatter the out path formatter
     * @return void
     */
    private function handleCurrentItem($name, &$pathFormatter)
    {
        $searching = true;

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

        return $searching;
    }

    /**
     * Allows to search and return the path formatter associated to the given route name.
     *
     * @param string $name the route name (must be unique)
     * @return PathFormatter the path formatter to use to generate the route
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
                $searching = $this->handleCurrentItem($name, $pathFormatter);
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
     * @param array $parameters the parameters to inject as path parameter
     * @param array $query the query parameter to add to the url.
     * @param string $fragment the fragment to set to the url.
     * @return sring the path url encoded
     * @throws KeyError when more than one route with the same name is discovered
     * @throws FormatException when formatting operation failed
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

    /**
     * Generate a serializable state of the route generator usable in constructor.
     *
     * @return array the key containing data to use to restore the state.
     */
    public function dumpState()
    {
        # Generate the stack offsets
        $stackOffsets = \array_map(
            function ($item) { return $item->key(); },
            $this->stack,
        );

        return [
            'hasNext' => $this->hasNext,
            'baseRoutes' => $this->baseRoutes,
            'generators' => $this->generators,
            'stackOffsets' => $stackOffsets,
            'routeIteratorOffset' => $this->routeIterator->valid() ? $this->routeIterator->key() : null, 
        ];
    }

}
