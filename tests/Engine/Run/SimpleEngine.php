<?php

namespace Fastwf\Tests\Engine\Run;

use Fastwf\Tests\Components\SimpleGuard;

use Fastwf\Core\Engine\Run\IRunnerEngine;
use Fastwf\Tests\Components\SimpleInPipe;
use Fastwf\Tests\Components\SimpleOutPipe;
use Fastwf\Tests\Components\SimpleInInterceptor;
use Fastwf\Tests\Components\SimpleOutInterceptor;
use Fastwf\Tests\Components\SimpleExceptionHandler;

class SimpleEngine implements IRunnerEngine {

    public function getConfiguration() {
        return [];
    }

    public function getMetadata() {
        return [];
    }

    public function getInputInterceptors() {
        return [new SimpleInInterceptor()];
    }

    public function getGuards() {
        return [new SimpleGuard()];
    }

    public function getInputPipes() {
        return [new SimpleInPipe()];
    }

    public function getOutputPipes() {
        return [new SimpleOutPipe()];
    }

    public function getOutputInterceptors() {
        return [new SimpleOutInterceptor()];
    }

    public function getExceptionHandlers() {
        return [new SimpleExceptionHandler()];
    }

    public function getService($class) {
        return null;
    }

    public function registerService($class, $instance) {
        // ignore
    }

}
