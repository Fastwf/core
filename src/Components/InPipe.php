<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Engine\Context;
use Fastwf\Core\Http\Frame\HttpRequest;

/**
 * Interface used after the guard control and before calling the request handler.
 * 
 * It can be used to perform request transformation or perform common task before handling the request.
 */
interface InPipe {
    /**
     * Perform action before request handler execution.
     *
     * @param Context $context the request context.
     * @param HttpRequest $request the client http request.
     * @return HttpRequest the same request or a transformed request.
     */
    public function in($context, $request);
}
