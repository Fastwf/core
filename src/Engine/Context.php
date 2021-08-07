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
     * Register a service for $class using the $instance object.
     * 
     * This can be usefull when the service must be instanciated in a specific way instead of default.
     *
     * @param string $class the class name associated to the instance
     * @param mixed $instance the instance to save as service
     */
    public function registerService($class, $instance);

}
