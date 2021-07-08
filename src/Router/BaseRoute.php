<?php

namespace Fastwf\Core\Router;

abstract class BaseRoute {

    protected $name;
    protected $path;

    public function __construct($path, $name) {
        $this->name = $name;
        $this->path = $path;
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
