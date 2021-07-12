<?php

namespace Fastwf\Core\Components;

/**
 * Interface used after the request handler and before calling the out interceptor.
 * 
 * It can be used to perform response transformation or perform common task afterbefore the request is handled.
 */
interface OutPipe {
    /**
     * Perform action after request handler execution.
     *
     * @param Fastwf\Core\Engine\Context $context the request context
     * @param mixed $request the client http request
     * @param mixed $response the http response
     * @return mixed the response transformed or the response in parameter
     */
    public function in($context, $request, $response);
}
