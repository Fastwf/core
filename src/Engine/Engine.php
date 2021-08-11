<?php

namespace Fastwf\Core\Engine;

use Fastwf\Core\Configuration;
use Fastwf\Core\Router\Mount;
use Fastwf\Core\Utils\ArrayProxy;
use Fastwf\Core\Engine\Output\ApacheHttpOutput;
use Fastwf\Core\Engine\Run\IRunnerEngine;
use Fastwf\Core\Engine\Run\Runner;
use Fastwf\Core\Http\HttpException;
use Fastwf\Core\Http\NotFoundException;
use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Http\Frame\HttpResponse;
use Fastwf\Core\Settings\ConfigurationSettings;
use Fastwf\Core\Settings\GuardSettings;
use Fastwf\Core\Settings\InputPipeSettings;
use Fastwf\Core\Settings\InputSettings;
use Fastwf\Core\Settings\OutputPipeSettings;
use Fastwf\Core\Settings\OutputSettings;
use Fastwf\Core\Settings\RouteSettings;

/**
 * The base class that allows to create and run a Fastwf application
 */
abstract class Engine implements Context, IRunnerEngine {

    private $configurationPath;

    private $settings;
    
    protected $server;
    protected $config;

    protected $metadata;

    protected $inputInterceptors = [];
    protected $routes = [];
    protected $guards = [];
    protected $inputPipes = [];
    protected $outPipes = [];
    protected $outputInterceptors = [];

    protected $services = [];

    protected $httpRequest = null;

    public function __construct($configurationPath = null) {
        // By default the application is loaded from /public/ folder and the configuration is set at root of the project
        //  When $configurationPath is not set, the configuration file is auto resolved
        $this->server = new ArrayProxy($_SERVER, true);

        if ($configurationPath === null) {
            $this->configurationPath = dirname($this->server->get('SCRIPT_FILENAME')) . '/../configuration.ini';
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
                $this->{$property} = \array_merge($this->{$property}, $setting->{$method}($this));
            }
        }
    }

    /// Private methods

    /**
     * Allows to setup the engine by loading the configuration and call extension to update the engine.
     */
    private function setup() {
        // Load the configuration
        $this->config = new Configuration($this->configurationPath);
        $this->onConfigurationLoaded();

        // Register the error handler
        $this->setErrorHandler();

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
    }

    /**
     * Create the request, perform preprocessing, create the response and post process the response.
     *
     * @return \Fastwf\Core\Http\Frame\HttpStreamResponse the response generated from incomming request.
     */
    private function handleRequest() {
        // Match the path with loaded routes
        try {
            $path = $this->server->get('REQUEST_URI');
            $method = \strtoupper($this->server->get('REQUEST_METHOD'));

            // Start the request life cycle on found route
            
            $match = $this->findRoute($path, $method);

            // Factory the http request and produce the http response
            $request = new HttpRequest($path, $method);
            $request->name = end($match['matchers'])->getName();
            $request->parameters = $match['parameters'];

            $httpResponse = (new Runner($this))->run(
                $request,
                $match
            );
        } catch (HttpException $httpException) {
            $httpResponse = $httpException->getResponse();
        } catch (\Exception $e) {
            // Execution error
            // TODO: Update this method to prevent traces in production
            $httpResponse = new HttpResponse(
                500,
                ['Content-Type' => 'text/plain'],
                "{$e->getMessage()}\n{$e->getTraceAsString()}",
            );
        }

        return $httpResponse;
    }

    /**
     * This methods help to find the route that correspond to the path and the methods of the http request.
     *
     * @param string $path the path of the request from the  '/'.
     * @param string $methods the http method requested.
     * @return array an array containing the list of Mount and Route for key 'matchers' and the list of extracted parameters for the key 'parameters'.
     */
    private function findRoute($path, $methods) {
        $mount = new Mount([
            'path' => $this->config->get('server.baseUrl', ''),
            'routes' => $this->routes,
            'name' => 'FastwfRoot',
        ]);
        
        $match = $mount->match(
            \substr($path, 1, \strlen($path) - 1),
            $methods,
        );

        if ($match === null) {
            // The route is not found, must return 404 response
            throw new NotFoundException("No match for '$path'");
        }

        return $match;
    }

    /// Protected methods

    protected function setErrorHandler() {
        \set_error_handler([$this, 'onError']);
    }

    /**
     * Send the response to the client
     *
     * @param Fastwf\Core\Http\Frame\HttpStreamResponse $response the http response.
     */
    protected function sendResponse($response) {
        // Open the stream to write http response
        $outputStream = \fopen(
            $this->config->get('server.output', 'php://output'),
            'w'
        );

        // Create the HttpOutput class to send the response to the client
        $response->send(
            $this->getHttpOutput($outputStream),
        );

        // Close the resource
        \fclose($outputStream);
    }

    /**
     * Create an http output instance to write the http response.
     *
     * @param resource $resource the stream where write the body
     * @return Fastwf\Core\Engine\Output\ApacheHttpOutput The HttpOutput implementation
     */
    protected function getHttpOutput($resource) {
        return new ApacheHttpOutput($resource);
    }

    /// Public interface

    public function onError($severity, $errMessage, $errFile = null, $errLine = null, $errContext = null) {
        throw new \ErrorException($errMessage, 0, $severity, $errFile, $errLine);
    }

    public function run() {
        // Load settings
        $this->settings = $this->getSettings();

        // Setup the engine
        $this->setup();

        // Create the response and send it to the client
        $this->sendResponse(
            $this->handleRequest()
        );
    }

    /// Implementation

    /**
     * {@inheritDoc}
     */
    public function getConfiguration() {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getService($class) {
        if (!\array_key_exists($class, $this->services)) {
            // Create the instance of the service using Service class constructor
            $this->services[$class] = new $class($this);
        }

        return $this->services[$class];
    }

    /**
     * {@inheritDoc}
     */
    public function registerService($class, $instance) {
        // The previous class is overriden by the new $instance parameter
        $this->services[$class] = $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getInputInterceptors() {
        return $this->inputInterceptors;
    }

    /**
     * {@inheritDoc}
     */
    public function getGuards() {
        return $this->guards;
    }

    /**
     * {@inheritDoc}
     */
    public function getInputPipes() {
        return $this->inputPipes;
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputPipes() {
        return $this->outPipes;
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputInterceptors() {
        return $this->outputInterceptors;
    }

}
