<?php

namespace Fastwf\Core\Engine\Run;

/**
 * The matcher must implements methods to access to life cycle components.
 */
interface IMatcher {

    /**
     * Access to matcher input interceptors.
     *
     * @return array the list of input interceptors.
     */
    public function getInputInterceptors();

    /**
     * Access to matcher guards.
     *
     * @return array the list of guards.
     */
    public function getGuards();

    /**
     * Access to matcher input pipes.
     *
     * @return array the list of input pipes.
     */
    public function getInputPipes();

    /**
     * Access to matcher output pipes.
     *
     * @return array the list of output pipes.
     */
    public function getOutputPipes();

    /**
     * Access to matcher output interceptors.
     *
     * @return array the list of output interceptors.
     */
    public function getOutputInterceptors();

    /**
     * Access to matcher exception handlers.
     *
     * @return array the list of exception handlers.
     */
    public function getExceptionHandlers();

}
