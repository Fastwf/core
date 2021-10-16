<?php

namespace Fastwf\Core\Engine;

/**
 * Context interface that allows to expose more Engine API.
 */
interface Context extends IEngine {

    /**
     * The method return a single service instance corresponding to the class in parameter.
     *
     * @param string $class the class name to get instance
     * @return mixed The service instanciated
     */
    public function getService($class);

    /**
     * Register a service for $class using the $instance object or implementation class name.
     * 
     * It's preferable to use factory function instead of instance to allows to create the instance only
     * when it's required by the application.
     * 
     * This can be usefull when the service must be instanciated in a specific way instead of default.
     *
     * @param string $class the class name associated to the instance
     * @param mixed $instance the service factory or the service istance to save.
     */
    public function registerService($class, $instance);

    /**
     * Access to the parsed request.
     * 
     * Can be null while the request is not processed.
     *
     * @return Fastwf\Core\Http\Frame\HttpRequest|null the request
     */
    public function getRequest();

}
