<?php

namespace Fastwf\Core\Engine\Run;

/**
 * Runner class to process the request and create the response.
 * 
 * The Runner class help to handle a request by running a request life cycle:
 * - InInterceptors
 * - Guard
 * - InPipe
 * - RequestHandler
 * - OutPipe
 * - OutInterceptors
 */
class Runner {

    /**
     * The engine that launched the runner.
     *
     * @var Fastwf\Core\Engine\IRunnerEngine
     */
    protected $engine;

    public function __construct($engine) {
        $this->engine = $engine;
    }

    /**
     * Handle the route by running the request life cycle.
     *
     * @param mixed $request
     * @param array $match the route to run.
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse the response produced by request handler.
     */
    public function run($request, $match) {
        $response = null;
        try
        {
            // Extract input interceptors and delegate input processing
            $request = $this->runStep($match, 'getInputInterceptors', 'start', [$this->engine, $request]);

            // Extract guards and control the request
            $this->runStep($match, 'getGuards', 'control', [$this->engine, $request]);

            // Extract input pipes and delegate request transformation
            $request = $this->runStep($match, 'getInputPipes', 'in', [$this->engine, $request]);

            // Generate the reqest handler and handle the request to generate the http response
            $route = end($match['matchers']);
            // Add name and parameters from matching route
            $request->name = $route->getName();
            $request->parameters = $match['parameters'];

            $response = $route->getHandler($this->engine)
                ->handle($request);

            // Extract out pipes and delegate post response transformation
            $response = $this->runStep($match, 'getOutputPipes', 'out', [$this->engine, $request, $response]);

            // Extract out interceptors and delegate post response processing
            $response = $this->runStep($match, 'getOutputInterceptors', 'end', [$this->engine, $request, $response]);
        }
        catch (\Throwable $e)
        {
            $response = $this->handleException($e, $match, $request, $response);
        }
        
        return $response;
    }

    private function runStep($match, $matcherMethod, $method, $args) {
        $components = \array_merge(
            $this->engine->{$matcherMethod}(),
            ...\array_map(
                function ($item) use ($matcherMethod) { return $item->{$matcherMethod}(); },
                $match['matchers'],
            ),
        );

        // By default return the last argument:
        //  input: request
        //  output: response
        $result = \end($args);
        foreach ($components as $component) {
            $result = $component->{$method}(...$args);
        }

        return $result;
    }

    /**
     * Handle the exception caught during request processing.
     *
     * @param \Throwable $exception
     * @param array $match the match array
     * @param Fastwf\Core\Http\Frame\HttpRequest $request the incomming request
     * @param Fastwf\Core\Http\Frame\HttpStreamResponse $response the response generated during request processing
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse the response in replacement
     * @throws \Throwable when exception handlers cannot treat the exception
     */
    private function handleException($exception, $match, $request, $response)
    {
        // Load exception handlers in the reverse order, but preserve the order in matcher definition
        $components = \array_merge(
            ...\array_reverse(
                \array_merge(
                    [$this->engine->getExceptionHandlers()],
                    ...\array_map(
                        function ($item) { return $item->getExceptionHandlers(); },
                        $match['matchers'],
                    )
                )
            )
        );

        // Try to handle the exception using registered exception handlers
        $exceptionResponse = null;
        foreach ($components as $component) {
            $exceptionResponse = $component->catch($exception, $request, $response);

            // Continue while the response is null because the exception is not handled
            if ($exceptionResponse !== null)
            {
                break;
            }
        }

        // When the exception is not handled, throw the exception to delegate to the engine
        if ($exceptionResponse === null)
        {
            throw $exception;            
        }

        return $exceptionResponse;
    }

}
