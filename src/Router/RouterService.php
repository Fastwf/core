<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Engine\Service;
use Fastwf\Core\Http\NotFoundException;

/**
 * RouterService that allows to manage router.
 */
class RouterService extends Service {

    private $router;

    public function __construct($context, $routes, $baseUrl) {
        $this->context = $context;

        $this->router = new Mount([
            'path' => $baseUrl,
            'routes' => $routes,
            'name' => 'FastwfRoot',
        ]);
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
    public function findRoute($path, $methods) {
        $match = $this->router->match(
            \substr($path, 1, \strlen($path) - 1),
            $methods,
        );

        if ($match === null) {
            // The route is not found, must return 404 response
            throw new NotFoundException("No match for '$path'");
        }

        return $match;
    }

}
