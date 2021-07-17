<?php

namespace Fastwf\Core\Settings;

/**
 * Engine extension setting that allows to define global output pipes.
 */
interface OutputPipeSettings {

    /**
     * Generate the global output pipes of the application.
     *
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @return array an array of OutPipes to apply to each requests
     */
    public function getOutputPipes($engine);

}
