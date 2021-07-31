<?php

namespace Fastwf\Core\Engine\Run;

/**
 * The matcher must implements methods to access to life cycle components.
 */
interface IMatcher {

    /**
     * Access to engine input interceptors.
     *
     * @return array the list of input interceptors.
     */
    public function getInputInterceptors();

    /**
     * Access to engine guards.
     *
     * @return array the list of guards.
     */
    public function getGuards();

    /**
     * Access to engine input pipes.
     *
     * @return array the list of input pipes.
     */
    public function getInputPipes();

    /**
     * Access to engine output pipes.
     *
     * @return array the list of output pipes.
     */
    public function getOutputPipes();

    /**
     * Access to engine output interceptors.
     *
     * @return array the list of output interceptors.
     */
    public function getOutputInterceptors();

}
