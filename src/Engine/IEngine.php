<?php

namespace Fastwf\Core\Engine;

/**
 * Public engine API exposed to settings implementations. 
 */
interface IEngine {

    /**
     * Allows to access to the engine configuration.
     *
     * @return Fastwf\Core\Configuration the loaded configuration
     */
    public function getConfiguration();

    /**
     * Allows to access to the server variable.
     *
     * @return \Fastwf\Core\Utils\ArrayProxy the $_SERVER array proxy
     */
    public function getServer();

    /**
     * Return the array proxy of engine metadata.
     *
     * @return \Fastwf\Core\Utils\ArrayProxy the metadata array proxy
     */
    public function getMetadata();

}
