<?php

namespace Fastwf\Core\Router\Parser;

use Fastwf\Core\Router\Segment;

/**
 * This route parser allows to parse the route and return segment object to be used to control and extract parameters.
 */
class SpecificationRouteParser extends RouteParser {

    public function current() {
        return new Segment($this->segment);
    }

}
