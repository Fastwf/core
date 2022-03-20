<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Engine\IEngine;

/**
 * Interface that allows to provide routes to engine.
 */
interface RouteSettings {
    
    /**
     * Generate the routes of the application.
     *
     * @param IEngine $engine the engine that request routes
     * @return array the array of BaseRoute
     */
    public function getRoutes($engine);

}
