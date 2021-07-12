<?php

namespace Fastwf\Core\Components;

/**
 * This interceptor allows to perform actions after the response is handled by output pipes.
 */
interface OutInterceptor {

    /**
     * Perform action before the response will write.
     *
     * @param Fastwf\Core\Engine\Context $context the request context
     * @param mixed $request the client http request
     * @param mixed $request the http response
     * @return mixed the response transformed or the response in parameter
     */
    public function end($context, $request, $response);

}
