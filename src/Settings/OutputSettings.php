<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Engine\IEngine;

/**
 * Engine extension setting that allows to define global OutInterceptor.
 */
interface OutputSettings {

    /**
     * Generate global OutInterceptor of the application.
     * 
     * @param IEngine $engine the executed engine
     * @return array an array of OutInterceptor
     */
    public function getOutputInterceptors($engine);

}
