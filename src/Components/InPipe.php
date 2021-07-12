<?php

namespace Fastwf\Core\Components;

/**
 * Interface used after the guard control and before calling the request handler.
 * 
 * It can be used to perform request transformation or perform common task before handling the request.
 */
interface InPipe {
    /**
     * Perform action before request handler execution.
     *
     * @param Fastwf\Core\Engine\Context $context the request context
     * @param mixed $request the client http request
     * @return void
     */
    public function in($context, $request);
}
