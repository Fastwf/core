<?php

/**
 * Engine extension setting that allows to define global input pipes.
 */
interface InputPipeSettings {

    /**
     * Generate the global input pipes of the application.
     *
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @return array an array of InPipes to apply to each requests
     */
    public function getInputPipes($engine);

}
