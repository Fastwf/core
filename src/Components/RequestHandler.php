<?php

namespace Fastwf\Core\Components;

/**
 * Application component that allows to handle the incoming client request.
 */
abstract class RequestHandler {

    /**
     * The engine context.
     *
     * @var Fastwf\Core\Engine\Context
     */
    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * Handle the request and return the response object to send to the client.
     *
     * @param Fastwf\Core\Http\Frame\HttpRequest $request the incoming the http request.
     * @return mixed the http reponse.
     */
    public abstract function handle($request);

}
