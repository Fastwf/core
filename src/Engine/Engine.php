<?php

namespace Fastwf\Core\Engine;

use Fastwf\Core\Configuration;
use Fastwf\Core\Utils\ArrayProxy;
use Fastwf\Core\Settings\ConfigurationSettings;
use Fastwf\Core\Settings\GuardSettings;
use Fastwf\Core\Settings\InputSettings;
use Fastwf\Core\Settings\InputPipeSettings;
use Fastwf\Core\Settings\RouteSettings;
use Fastwf\Core\Settings\OutputSettings;
use Fastwf\Core\Settings\OutputPipeSettings;

/**
 * The base class that allows to create and run a Fastwf application
 */
abstract class Engine implements Context {

    private $configurationPath;

    private $settings;
    
    protected $config;

    protected $metadata;

    protected $inputInterceptors;
    protected $routes;
    protected $guards;
    protected $inputPipes;
    protected $outPipes;
    protected $outputInterceptors;

    public function __construct($configurationPath = null) {
        // By default the application is loaded from /public/ folder and the configuration is set at root of the project
        //  When $configurationPath is not set, the configuration file is auto resolved
        if ($configurationPath === null) {
            $this->configurationPath = dirname($_SERVER['SCRIPT_FILENAME']) . '/../configuration.ini';
        } else {
            $this->configurationPath = $configurationPath;
        }

        $this->metadata = new ArrayProxy();
    }

    /// Abstraction layer

    /**
     * Allows to load the application tunes that allows to configure the application.
     *
     * @return array
     */
    protected abstract function getSettings();

    /// Private interface

    /**
     * Extend the configuration thanks to external modules.
     */
    private function onConfigurationLoaded() {
        foreach ($this->settings as $setting) {
            if ($setting instanceof ConfigurationSettings) {
                $setting->configure($this, $this->config);
            }
        }
    }

    /**
     * Load additionnal items in the array responding to the name $property when the settings is instance of $class.
     *
     * @param string $class the settings class to filter
     * @param string $method the method name to call of the $class
     * @param string $property the property name of the array to populate
     */
    private function load($class, $method, $property) {
        foreach ($this->settings as $setting) {
            if ($setting instanceof $class) {
                $this->{$property} = \array_merge($this->$property, $setting->{$method}($this));
            }
        }
    }

    /**
     * Allows to setup the engine by loading the configuration and call extension to update the engine.
     */
    private function setup() {
        // Load the configuration
        $this->config = new Configuration($this->configurationPath);
        $this->onConfigurationLoaded();

        // Load the routes from settings
        $this->load(RouteSettings::class, 'getRoutes', 'routes');

        // Register global inInterceptors
        $this->load(InputSettings::class, 'getInputInterceptors', 'inputInterceptors');
        // Register global guards
        $this->load(GuardSettings::class, 'getGuards', 'guards');
        // Register global inPipes
        $this->load(InputPipeSettings::class, 'getInputPipes', 'inputPipes');
        // Register global outPipes
        $this->load(OutputPipeSettings::class, 'getOutputPipes', 'outputPipes');
        // Register global outInterceptors
        $this->load(OutputSettings::class, 'getOutputInterceptors', 'outputInterceptors');

        // TODO: Create the request
        // TODO: Match the path with loaded routes
        // TODO: Start the request life cycle
    }

    /// Public interface

    public function run() {
        // Load settings
        $this->settings = $this->getSettings();

        // Setup the engine
        $this->setup();
    }

    /// Implementation

    public function getConfiguration() {
        return $this->config;
    }

    public function getMetadata() {
        return $this->metadata;
    }

}
