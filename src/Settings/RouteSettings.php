<?php

namespace Fastwf\Core\Settings;

/**
 * Interface that allows to provide routes to engine.
 */
interface RouteSettings {
    
    /**
     * Generate the routes of the application.
     *
     * @param Fastwf\Core\Engine\IEngine $engine the engine that request routes
     * @return array the array of BaseRoute
     */
    public function getRoutes($engine);

}
