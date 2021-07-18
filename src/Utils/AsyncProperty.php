<?php

namespace Fastwf\Core\Utils;

/**
 * Property storage that can use a factory to create the value.
 * 
 * This utility class allows to generate the value only when it's required and access to it when it's is required.
 */
class AsyncProperty {
    
    protected $set = false;
    protected $value = null;
    protected $asyncValue = null;

    public function __construct($value) {
        if (\is_callable($value)) {
            $this->asyncValue = $value;
        } else {
            $this->value = $value;
            $this->set = true;
        }
    }

    public function __invoke() {
        return $this->get();
    }

    public function get() {
        if (!$this->set) {
            $this->value = \call_user_func($this->asyncValue);
            $this->set = true;
        }

        return $this->value;
    }

}
