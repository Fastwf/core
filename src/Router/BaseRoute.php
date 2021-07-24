<?php

namespace Fastwf\Core\Router;

abstract class BaseRoute {

    protected $name;
    protected $path;

    protected $inputInterceptors;
    protected $guards;
    protected $inputPipes;
    protected $outputPipes;
    protected $outputInterceptors;

    /**
     * Constructor.
     *
     * @param string $path the path associated to the route (must not start with '/')
     * @param array $inputInterceptors the array of input interceptors attached to the route
     * @param array $guards the array of guards attached to the route
     * @param array $inputPipes the array of input pipes attached to the route
     * @param array $outputPipes the array of output pipes attached to the route
     * @param array $outputInterceptors the array of output interceptors attached to the route
     * @param string|null $name
     */
    public function __construct($path, $inputInterceptors = [], $guards = [], $inputPipes = [], $outputPipes = [],
                                $outputInterceptors = [], $name = null) {
        $this->path = $path;
        $this->name = $name;

        $this->inputInterceptors = $inputInterceptors;
        $this->guards = $guards;
        $this->inputPipes = $inputPipes;
        $this->outputPipes = $outputPipes;
        $this->outputInterceptors = $outputInterceptors;
    }

    /**
     * Allows to perform matching on path and method.
     *
     * @param string $path the request path
     * @param string $method the request method
     * @return array|null null when match failed or an array of extracted parameters when full match
     */
    public abstract function match($path, $method);

}
