<?php

namespace Fastwf\Core\Exceptions;

/**
 * Class used to throw an attribute exception.
 */
class AttributeError extends \Exception {

    public function __construct($name) {
        parent::__construct("Attribute '$name' not found");
    }

}
