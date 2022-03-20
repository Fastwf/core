<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Engine\IEngine;

/**
 * Engine extension setting that allows to define global input pipes.
 */
interface InputPipeSettings {

    /**
     * Generate the global input pipes of the application.
     *
     * @param IEngine $engine the executed engine
     * @return array an array of InPipes to apply to each requests
     */
    public function getInputPipes($engine);

}
