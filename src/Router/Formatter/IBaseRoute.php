<?php

namespace Fastwf\Core\Router\Formatter;

/**
 * BaseRoute model interface.
 */
interface IBaseRoute
{

    /**
     * Get the name of the route when it's set
     *
     * @return string
     */
    public function getName();

    /**
     * Get the path associated to this route.
     *
     * @return string
     */
    public function getPath();

}
