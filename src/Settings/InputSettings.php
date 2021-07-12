<?php

namespace Fastwf\Core\Settings;

/**
 * Engine extension setting that allows to define global InInterceptor.
 */
interface InputSettings {

    /**
     * Generate global InInterceptors of the application.
     * 
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @return array an array of InInterceptor
     */
    public function getInputInterceptors($engine);

}
