<?php

namespace Fastwf\Core\Router;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Api\Utils\AsyncProperty;
use Fastwf\Core\Engine\Run\IMatcher;
use Fastwf\Core\Router\Formatter\IBaseRoute;

abstract class BaseRoute implements IBaseRoute, IMatcher {

    protected $name;
    protected $path;

    protected $inputInterceptors;
    protected $guards;
    protected $inputPipes;
    protected $outputPipes;
    protected $outputInterceptors;
    protected $exceptionHandlers;

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

        $this->inputInterceptors = new AsyncProperty(ArrayUtil::getSafe($params, "inputInterceptors", []));
        $this->guards = new AsyncProperty(ArrayUtil::getSafe($params, "guards", []));
        $this->inputPipes = new AsyncProperty(ArrayUtil::getSafe($params, "inputPipes", []));
        $this->outputPipes = new AsyncProperty(ArrayUtil::getSafe($params, "outputPipes", []));
        $this->outputInterceptors = new AsyncProperty(ArrayUtil::getSafe($params, "outputInterceptors", []));
        $this->exceptionHandlers = new AsyncProperty(ArrayUtil::getSafe($params, "exceptionHandlers", []));
    }

    /**
     * Allows to perform matching on path and method.
     *
     * @param string $path the request path
     * @param string $method the request method
     * @return array|null null when match failed or an array of extracted parameters and BaseRoute list when full match
     */
    public abstract function match($path, $method);

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath() {
        return $this->path;
    }

    /// Implementation

    /**
     * {@inheritDoc}
     */
    public function getInputInterceptors() {
        return $this->inputInterceptors->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getGuards() {
        return $this->guards->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getInputPipes() {
        return $this->inputPipes->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputPipes() {
        return $this->outputPipes->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputInterceptors() {
        return $this->outputInterceptors->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getExceptionHandlers() {
        return $this->exceptionHandlers->get();
    }

    /**
     * Generate the parameter name sequence according to the route name and parameter name.
     *
     * @param string|null $routeName the name of the route or mount point
     * @param string $parameterName the name of the segment's parameter.
     * @return string the qualified parameter name ($parameterName or "$routeName/$parameterName";)
     */
    public static function getParameterName($routeName, $parameterName) {
        if ($routeName === null || trim($routeName) === "") {
            return $parameterName;
        } else {
            return "$routeName/$parameterName";
        }
    }

}
