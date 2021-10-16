<?php

namespace Fastwf\Core\Engine;

use Fastwf\Core\Utils\AsyncProperty;

/**
 * Provider responsible of services.
 */
class ServiceProvider
{

    /**
     * The engine contains that use the service provider.
     *
     * @var Fastwf\Core\Engine\Context
     */
    private $context;

    /**
     * The array containing all services.
     *
     * @var array
     */
    protected $services;
    
    /**
     * An array containing the class name corresponding to service name.
     * 
     * This property must be used to create instance of services corresponding to service interface or abstract services.
     *
     * @var array
     */
    protected $implementations;

    public function __construct($context)
    {
        $this->context = $context;

        $this->services = [];
        $this->implementations = [];
    }

    /**
     * Try to find the implementation class corresponding to the given interface class name.
     *
     * @param string $interface the name of the service interface or implementation class.
     * @return string the registered implementation class name associated to the interface class or interface itself when it is not 
     *                registered.
     */
    private function getImplementationClass($interface)
    {
        return \array_key_exists($interface, $this->implementations)
            ? $this->implementations[$interface]
            : $interface;
    }

    /**
     * Create the service instance corresponding to the service name.
     *
     * @param string $class the name, service interface name or service implementation class name
     * @return void
     */
    private function createServiceInstance($class)
    {
        $serviceClass = $this->getImplementationClass($class);

        // Create the instance of the original service using Service class constructor
        if (!\array_key_exists($serviceClass, $this->services))
        {
            $this->services[$serviceClass] = new AsyncProperty(new $serviceClass($this->context));
        }

        // When the $serviceClass and the $class are not equals (Interface & Implementation):
        //  register the implementation as interface service 
        if ($serviceClass !== $class)
        {
            $this->services[$class] = $this->services[$serviceClass];
        }
    }

    /// PUBLIC API

    /**
     * Get an unique instance of the service requested using class name.
     * 
     * @param string $class the name of the service class.
     * @return mixed the instance of the service.
     */
    public function getService($class)
    {
        if (!\array_key_exists($class, $this->services))
        {
            // Create the instance of the service using Service class constructor
            $this->createServiceInstance($class);
        }

        return $this->services[$class]->get();
    }

    /**
     * Register a service for $class using the $instance object or factory function.
     *
     * @param string $class the class name associated to the instance.
     * @param mixed $instance the service factory or the service istance to save.
     */
    public function registerService($class, $instance)
    {
        // The previous class is overriden by the new $instance parameter
        $this->services[$class] = new AsyncProperty($instance);
    }

    /**
     * Register a class name to use when the service interface is requested from this provider.
     *
     * @param string $interface the name of the service interface.
     * @param string $class the class name that implements the service interface.
     * @return void
     */
    public function setServiceImplementation($interface, $class)
    {
        $this->implementations[$interface] = $class;
    }

}
