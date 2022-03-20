<?php

namespace Fastwf\Core\Settings;

use Fastwf\Core\Configuration;
use Fastwf\Core\Engine\IEngine;

/**
 * Configuration listener that allows to setup engine.
 */
interface ConfigurationSettings {

    /**
     * Perform additionnal engine settings
     *
     * @param IEngine $engine the executed engine
     * @param Configuration $configuration the loaded configuration.
     */
    public function configure($engine, $configuration);

}
