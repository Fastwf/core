<?php

namespace Fastwf\Core\Session;

/**
 * Session service interface.
 */
interface SessionService
{

    /**
     * Access to non locked session object container.
     * 
     * The data is loaded but the session data is not locked.
     *
     * @return Fastwf\Core\Session\Session the session object wrapper
     */
    public function getSession();

    /**
     * Access to a locked session object container.
     * 
     * The session data is preserved while closeSession() method is not called.
     * This method allows to prevent concurrent requests to update sensible session data.
     *
     * @return Fastwf\Core\Session\Session the session object wrapper
     */
    public function getLockedSession();

    /**
     * Write session data and close the session access.
     *
     * @return void
     */
    public function closeSession();

}
