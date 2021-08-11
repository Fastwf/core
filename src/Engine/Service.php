<?php

namespace Fastwf\Core\Engine;

/**
 * Base service class that allows application service to access to the engine context.
 */
abstract class Service {

    /**
     * The engine context executed
     *
     * @var Fastwf\Core\Engine\Context
     */
    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

}
