<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Engine\Context;
use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Http\Frame\HttpStreamResponse;

/**
 * This interceptor allows to perform actions after the response is handled by output pipes.
 */
interface OutInterceptor {

    /**
     * Perform action before the response will write.
     *
     * @param Context $context the request context
     * @param HttpRequest $request the client http request
     * @param HttpStreamResponse $request the http response
     * @return HttpStreamResponse the response transformed or the response in parameter
     */
    public function end($context, $request, $response);

}
