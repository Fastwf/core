<?php

namespace Fastwf\Core\Settings;

/**
 * Engine extension setting that allows to define global exception handlers.
 */
interface ExceptionSettings {

    /**
     * Generate the global output pipes of the application.
     *
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @return array an array of ExceptionHandler to use when an exception is not handled
     */
    public function getExceptionHandlers($engine);

}
