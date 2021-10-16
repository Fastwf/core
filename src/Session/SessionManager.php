<?php

namespace Fastwf\Core\Session;

use Fastwf\Core\Engine\Service;
use Fastwf\Core\Session\SessionService;

/**
 * Interface that allows to handle session during the request.
 */
abstract class SessionManager extends Service implements SessionService
{

    /**
     * Indicate if the session is started or not.
     *
     * This variable allows to know if the session was open at least once.
     * 
     * @var boolean
     */
    protected $isStarted;

    /**
     * Indicate if the session open or not.
     * 
     * This variable is associated to the session access (lock or not).
     *
     * @var boolean
     */
    protected $isOpened;

    public function __construct($context)
    {
        parent::__construct($context);

        $this->isStarted = false;
        $this->isOpened = false;
    }

    /// Protected cookie setup methods

    protected abstract function getCookieName();

    protected abstract function getCookieDomain();

    protected abstract function getCookiePath();

    protected abstract function getCookieHttpOnly();

    protected abstract function getCookieSecure();

    protected abstract function getCookieSameSite();

    /**
     * Return the cookie life time.
     * 
     * Return 0 to persist session cookie while the navigator is open.
     *
     * @return integer The cookie life time in second.
     */
    protected abstract function getCookieLifeTime();

    /// PUBLIC API

    /**
     * Get the current session id or generate and return the session id.
     *
     * @return string the session id.
     */
    public abstract function getSessionId();

}
