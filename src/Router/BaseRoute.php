<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Utils\ArrayUtil;

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
     * Parameters:
     * - "path": [required] The path of the route
     * - "inputInterceptors": [optional]  The list of inputInterceptors to apply
     * - "guards": [optional] The list of guards to apply
     * - "inputPipes": [optional] The list of inputPipes to apply
     * - "outputPipes": [optional] The list of outputPipes to apply
     * - "outputInterceptors": [optional] The list of outputInterceptors to apply
     * - "name": [optional] The name of the route
     * 
     * @param array $params An array containing the constructor parameters.
     */
    public function __construct($params) {
        $this->path = ArrayUtil::get($params, "path");
        $this->name = ArrayUtil::getSafe($params, "name");

        $this->inputInterceptors = ArrayUtil::getSafe($params, "inputInterceptors", []);
        $this->guards = ArrayUtil::getSafe($params, "guards", []);
        $this->inputPipes = ArrayUtil::getSafe($params, "inputPipes", []);
        $this->outputPipes = ArrayUtil::getSafe($params, "outputPipes", []);
        $this->outputInterceptors = ArrayUtil::getSafe($params, "outputInterceptors", []);
    }

    /**
     * Allows to perform matching on path and method.
     *
     * @param string $path the request path
     * @param string $method the request method
     * @return array|null null when match failed or an array of extracted parameters and BaseRoute list when full match
     */
    public abstract function match($path, $method);

}
