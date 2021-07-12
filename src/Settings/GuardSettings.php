<?php

/**
 * Engine extension setting that allows to define global guard access.
 */
interface GuardSettings {

    /**
     * Generate the global guard of the application.
     *
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @return array an array of guards to apply to each requests
     */
    public function getGuards($engine);

}
