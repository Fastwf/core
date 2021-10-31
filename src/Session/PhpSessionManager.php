<?php

namespace Fastwf\Core\Session;

use Fastwf\Core\Session\Session;
use Fastwf\Core\Session\SessionManager;

/**
 * SessionManager implementation that use PHP built-in session functions.
 */
class PhpSessionManager extends SessionManager
{

    protected $session = null;

    /**
     * Generate the common session options (for concurrent or not access)
     *
     * @return array the common session options
     */
    private function getSessionOptions()
    {
        return [
            'name' => $this->getCookieName(),
            'cookie_domain' => $this->getCookieDomain(),
            'cookie_path' => $this->getCookiePath(),
            'cookie_secure' => $this->getCookieHttpOnly(),
            'cookie_httponly' => $this->getCookieSecure(),
            'cookie_samesite' => $this->getCookieSameSite(),
            'cookie_lifetime' => $this->getCookieLifeTime(),
        ];
    }

    protected function getCookieName()
    {
        // Override the default name to prevent identification of PHP backend language
        return 'SESSION_ID';
    }

    protected function getCookieDomain()
    {
        return \ini_get('session.cookie_domain');
    }

    protected function getCookiePath()
    {
        // Use the base url instead of php.ini value
        return "/" . $this->context
            ->getConfiguration()
            ->get('server.baseUrl', '');
    }

    protected function getCookieHttpOnly()
    {
        return \ini_get('session.cookie_httponly');
    }

    protected function getCookieSecure()
    {
        return \ini_get('session.cookie_secure');
    }

    protected function getCookieSameSite()
    {
        return \ini_get('session.cookie_samesite');
    }

    protected function getCookieLifeTime()
    {
        $lifeTime = \ini_get('session.cookie_lifetime');

        // php.ini values are strings -> convert to int when it's possible
        return \is_numeric($lifeTime)
            ? \intval($lifeTime)
            : 0;
    }

    /**
     * Open the session by using the session options.
     *
     * @param boolean $lock true when the session must be opened without close.
     * @param boolean $refresh true to refresh the content of non locked session.
     * @return void
     */
    protected function startSession($lock, $refresh)
    {
        // When it's already open, the process can be stopped
        if (!$this->isOpened) {
            $options = $this->getSessionOptions();

            $mustStart = true;
            if ($lock)
            {
                // Mark the session opened
                $this->isOpened = true;
            }
            else if ($refresh || $this->session === null)
            {
                $options['read_and_close'] = true;
            }
            else
            {
                $mustStart = false;
            }

            if ($mustStart)
            {
                // When the session must be locked or refreshed, call \session_start with right options.

                // Start the session and load the session variable
                \session_start($options);
                $this->isStarted = true;

                // Create or refresh the session object
                if ($this->session === null)
                {
                    $this->session = new Session($_SESSION);
                }
                else
                {
                    $this->session->refresh($_SESSION);
                }
            }
        }
    }

    /// PUBLIC API

    public function getSessionId()
    {
        // The PHP session_start built-in function must be called to obtain the session id
        $this->startSession(false, false);

        return \session_id();
    }

    public function getSession($refresh=false)
    {
        $this->startSession(false, $refresh);

        return $this->session;
    }

    public function getLockedSession()
    {
        $this->startSession(true, false);

        return $this->session;
    }

    /**
     * {@inheritDoc}
     */
    public function closeSession()
    {
        if ($this->isStarted)
        {
            // When the session is started, it must be closed

            if ($this->isOpened)
            {
                // The session is open is lock mode, it's just required to write changes
                \session_write_close();

                $this->isOpened = false;

                $this->session = null;
            }
            else if ($this->session->isModified())
            {
                // The session is not open, so when session_start will be called, $_SESSION will be reloaded
                \session_start($this->getSessionOptions());
                // Apply session modification to super global reloaded
                $this->session->applyModifications($_SESSION);
                // Save the modifications
                \session_write_close();

                $this->session = null;
            }

            $this->isStarted = false;
        }
    }

}
