<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Engine\IEngine;

/**
 * Engine extension setting that allows to define global InInterceptor.
 */
interface InputSettings {

    /**
     * Generate global InInterceptors of the application.
     * 
     * @param IEngine $engine the executed engine
     * @return array an array of InInterceptor
     */
    public function getInputInterceptors($engine);

}
