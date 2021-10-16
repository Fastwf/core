<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Engine\Service;
use Fastwf\Core\Http\NotFoundException;
use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Formatter\RouteGenerator;
use Fastwf\Core\Utils\AsyncProperty;

/**
 * RouterService that allows to manage router.
 */
class RouterService extends Service {

    public const ROUTE_STATE_NAME = "router-service.route-generator-state";

    private $generator;
    private $router;

    public function __construct($context, $routes, $baseUrl)
    {
        $this->context = $context;

        $this->router = new Mount([
            'path' => $baseUrl,
            'routes' => $routes,
            'name' => 'FastwfRoot',
        ]);
        $this->generator = new AsyncProperty(function () {
            return $this->restoreRouteGenerator();
        });
    }

    /// PRIVATE METHODS

    /**
     * Generate and return the path to the route generator state file
     *
     * @return string the path to the file.
     */
    private function getRouteGeneratorPath() {
        return $this->context->getCachePath('fastwf.core')
            . DIRECTORY_SEPARATOR
            . self::ROUTE_STATE_NAME;
    }

    /// PUBLIC METHODS

    /**
     * This methods help to find the route that correspond to the path and the methods of the http request.
     *
     * @param string $path the path of the request from the  '/'.
     * @param string $methods the http method requested.
     * @return array an array containing the list of Mount and Route for key 'matchers' and the list of extracted parameters for the key 
     *               'parameters'.
     * @throws Fastwf\Core\Http\NotFoundException
     */
    public function findRoute($path, $methods)
    {
        $match = $this->router->match(
            \substr($path, 1, \strlen($path) - 1),
            $methods,
        );

        if ($match === null)
        {
            // The route is not found, must return 404 response
            throw new NotFoundException("No match for '$path'");
        }

        return $match;
    }

    /**
     * Search and generate the path associated to the route name (the first).
     *
     * @param string $name the name of the route.
     * @param string $parameters the parameters to inject as path parameter
     * @param string $query the query parameter to add to the url.
     * @param string $fragment the fragment to set to the url.
     * @return sring the path url encoded
     */
    public function urlFor($name, $parameters = null, $query = null, $fragment = null)
    {
        return $this->generator->get()
            ->generate($name, $parameters, $query, $fragment);
    }

    /**
     * Serialize the route generator when the engine run in production mode and the cache directory is accessible.
     *
     * In production mode, the application routes are not updated, so it's not required for RouteGenerator class to restart from it's
     * initial state.
     * By caching it's state, performances of generate method call are improved.
     * 
     * @return void
     */
    public function dumpRouteGenerator()
    {
        $configuration = $this->context->getConfiguration();

        if ($configuration->getBoolean('server.modeProduction', false))
        {
            \file_put_contents(
                $this->getRouteGeneratorPath(),
                \serialize($this->generator->get()->dumpState())
            );
        }
    }

    /**
     * Try to find the cache file of RouteGenerator state and try to recreate the instance from this state.
     * 
     * Warning: when production mode is disabled, the method return always null. 
     *
     * @return Fastwf\Core\Router\Formatter\RouteGenerator|null the route generator when it's possible to restore the state.
     */
    public function restoreRouteGenerator()
    {
        // Load the route generator state and re create the instance if it's required
        $configuration = $this->context->getConfiguration();

        $path = $this->getRouteGeneratorPath();
        if ($configuration->get('server.modeProduction', false) 
            && \file_exists($path)
            && \is_file($path)
            && \is_readable($path))
        {
            try
            {
                $state = \unserialize(
                    \file_get_contents($path)
                );
            }
            catch (\ErrorException $e)
            {
                // The unserialization failed
                $state = null;
            }

            return new RouteGenerator($this->router, $state);
        }

        return new RouteGenerator($this->router);
    }

}
