<?php

namespace Fastwf\Core\Components;

/**
 * The guard interface.
 * 
 * A guard allows to perform control on the request and allow or deny the resource access.
 */
interface Guard {

    /**
     * Control the request context to allow or deny resource access. 
     *
     * @param Fastwf\Core\Engine\Context $context the request context
     * @param mixed $request the client http request
     * @throws Fastwf\Http\HttpException exception that prevent request handler execution 
     */
    public function control($context, $request);

}
