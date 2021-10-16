<?php

namespace Fastwf\Core\Session\Components;

use Fastwf\Core\Session\SessionService;
use Fastwf\Core\Components\OutInterceptor;

/**
 * SessionShutdown component
 * 
 * This component allows to close the session when it's not already done before.
 */
class SessionShutdown implements OutInterceptor
{

    /**
     * Close the session using the SessionService
     */
    public function end($context, $request, $response)
    {
        $context->getService(SessionService::class)
            ->closeSession();

        return $response;
    }

}
