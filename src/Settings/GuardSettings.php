<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Engine\IEngine;

/**
 * Engine extension setting that allows to define global guard access.
 */
interface GuardSettings {

    /**
     * Generate the global guard of the application.
     *
     * @param IEngine $engine the executed engine
     * @return array an array of guards to apply to each requests
     */
    public function getGuards($engine);

}
