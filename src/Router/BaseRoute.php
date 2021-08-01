<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Utils\ArrayUtil;
use Fastwf\Core\Utils\AsyncProperty;
use Fastwf\Core\Engine\Run\IMatcher;

abstract class BaseRoute implements IMatcher {

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

        $this->inputInterceptors = new AsyncProperty(ArrayUtil::getSafe($params, "inputInterceptors", []));
        $this->guards = new AsyncProperty(ArrayUtil::getSafe($params, "guards", []));
        $this->inputPipes = new AsyncProperty(ArrayUtil::getSafe($params, "inputPipes", []));
        $this->outputPipes = new AsyncProperty(ArrayUtil::getSafe($params, "outputPipes", []));
        $this->outputInterceptors = new AsyncProperty(ArrayUtil::getSafe($params, "outputInterceptors", []));
    }

    /**
     * Allows to perform matching on path and method.
     *
     * @param string $path the request path
     * @param string $method the request method
     * @return array|null null when match failed or an array of extracted parameters and BaseRoute list when full match
     */
    public abstract function match($path, $method);

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

}
