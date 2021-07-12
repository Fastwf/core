<?php

namespace Fastwf\Core\Components;

/**
 * This interceptor allows to perform actions after the request is created and before the route guard is called.
 */
interface InInterceptor {

    /**
     * Perform action before any logic system.
     *
     * @param Fastwf\Core\Engine\Context $context
     * @param mixed $request the client http request
     */
    public function start($context, $request);

}
