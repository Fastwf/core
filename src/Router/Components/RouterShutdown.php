<?php

namespace Fastwf\Core\Router\Components;

use Fastwf\Core\Components\OutInterceptor;
use Fastwf\Core\Router\RouterService;

/**
 * Out interceptor to use to shutdown the router system.
 * 
 * This class implements OutInterceptor to allows to perform shutdown operation on router system.
 */
class RouterShutdown implements OutInterceptor
{

    /**
     * {@inheritDoc}
     */
    public function end($context, $request, $response)
    {
        $routerService = $context->getService(RouterService::class);

        // Call the service to dump the route generator state
        $routerService->dumpRouteGenerator();

        return $response;
    }
    
}
