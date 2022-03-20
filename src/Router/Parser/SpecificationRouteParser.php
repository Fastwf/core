<?php

namespace Fastwf\Core\Router\Parser;

use Fastwf\Core\Router\Segment;

/**
 * This route parser allows to parse the route and return segment object to be used to control and extract parameters.
 */
class SpecificationRouteParser extends RouteParser {

    protected $specificationSegment = null;

    protected function nextSegment() {
        parent::nextSegment();

	if ($this->segment) {
            $this->specificationSegment = new Segment($this->segment);
	}
    }

    public function next(): void {
        // Stop the iteration when a path segment is discovered
        //  That allows to return the same segment to accumulate the path as parameter
        if ($this->specificationSegment === null || !$this->specificationSegment->isPath()) {
            parent::next();
        }
    }

    public function current() {
        return $this->specificationSegment;
    }

}
