<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Components\Guard;

class SimpleGuard implements Guard {

    public function control($context, $request) {
        // Ignore
    }

}
