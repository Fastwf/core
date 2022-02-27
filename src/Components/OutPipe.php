<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Engine\Context;
use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Http\Frame\HttpStreamResponse;

/**
 * Interface used after the request handler and before calling the out interceptor.
 * 
 * It can be used to perform response transformation or perform common task afterbefore the request is handled.
 */
interface OutPipe {
    /**
     * Perform action after request handler execution.
     *
     * @param Context $context the request context
     * @param HttpRequest $request the client http request
     * @param HttpStreamResponse $response the http response
     * @return HttpStreamResponse the response transformed or the response in parameter
     */
    public function out($context, $request, $response);
}
