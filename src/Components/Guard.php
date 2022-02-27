<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Engine\Context;
use Fastwf\Core\Http\HttpException;
use Fastwf\Core\Http\Frame\HttpRequest;

/**
 * The guard interface.
 * 
 * A guard allows to perform control on the request and allow or deny the resource access.
 */
interface Guard {

    /**
     * Control the request context to allow or deny resource access. 
     *
     * @param Context $context the request context
     * @param HttpRequest $request the client http request
     * @throws HttpException exception that prevent request handler execution 
     */
    public function control($context, $request);

}
