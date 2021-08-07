<?php

namespace Fastwf\Core\Engine;

abstract class Service {

    protected $context;

    public final function __construct($context) {
        $this->context = $context;
    }

}
