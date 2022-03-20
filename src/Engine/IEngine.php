<?php

namespace Fastwf\Core\Engine;

use Fastwf\Core\Configuration;
use Fastwf\Api\Utils\ArrayProxy;

/**
 * Public engine API exposed to settings implementations. 
 */
interface IEngine {

    /**
     * Allows to access to the engine configuration.
     *
     * @return Configuration the loaded configuration
     */
    public function getConfiguration();

    /**
     * Allows to access to the server variable.
     *
     * @return ArrayProxy the $_SERVER array proxy
     */
    public function getServer();

    /**
     * Return the array proxy of engine metadata.
     *
     * @return ArrayProxy the metadata array proxy
     */
    public function getMetadata();

    /**
     * Get the root path of the project.
     * 
     * By default the value is computed from runtime environment (DOCUMENT_ROOT + '/..') but, the
     * value can be provided by "server.rootPath" configuration key.
     *
     * @return string
     */
    public function getRootPath();

    /**
     * Get the var path of the project.
     * 
     * By default the value is computed from runtime environment (rootPath + '/var') but, the
     * value can be provided by "server.varPath" configuration key.
     *
     * @return string
     */
    public function getVarPath();

    /**
     * The engine provide a safe cache path where data can be persisted for the given namespace.
     *
     * @param string $namespace the namespace to use for cache (for unicity use "author.project" naming convention)
     * @return string
     */
    public function getCachePath($namespace);

}
