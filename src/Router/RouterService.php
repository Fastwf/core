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
            return new RouteGenerator($this->router);
        });
    }

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
        // TODO: reload the RouteGenerator from a serialized cache for production mode
        return $this->generator->get()
            ->generate($name, $parameters, $query, $fragment);
    }

}
