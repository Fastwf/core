<?php

namespace Fastwf\Tests\Engine;

use Fastwf\Core\Engine\Engine;
use Fastwf\Core\Settings\ConfigurationSettings;

use Fastwf\Tests\Engine\SimpleSettings;

class SimpleEngine extends Engine implements ConfigurationSettings {

    public function getSettings() {
        return [
            $this,
            new SimpleSettings(),
        ];
    }

    public function configure($engine, $configuration) {
        $engine->getMetadata()->set('application', $configuration->get('app.name', 'Fastwf Tests'));
    }

}
