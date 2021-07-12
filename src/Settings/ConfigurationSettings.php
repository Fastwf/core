<?php

namespace Fastwf\Core\Settings;

/**
 * Configuration listener that allows to setup engine.
 */
interface ConfigurationSettings {

    /**
     * Perform additionnal engine settings
     *
     * @param Fastwf\Core\Engine\IEngine $engine the executed engine
     * @param Fastwf\Core\Configuration $configuration the loaded configuration.
     */
    public function configure($engine, $configuration);

}
