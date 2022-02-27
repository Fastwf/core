<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Engine\Context;
use Fastwf\Core\Http\Frame\HttpRequest;

/**
 * This interceptor allows to perform actions after the request is created and before the route guard is called.
 */
interface InInterceptor {

    /**
     * Perform action before any logic system.
     *
     * @param Context $context.
     * @param HttpRequest $request the client http request.
     * @return HttpRequest the same request or a transformed request.
     */
    public function start($context, $request);

}
