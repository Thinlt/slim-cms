<?php

namespace Model\Varien;


class Session extends \Model\Varien\Object
{
    const VALIDATOR_KEY                         = '_session_validator_data';
    const VALIDATOR_HTTP_USER_AGENT_KEY         = 'http_user_agent';
    const VALIDATOR_HTTP_X_FORVARDED_FOR_KEY    = 'http_x_forwarded_for';
    const VALIDATOR_HTTP_VIA_KEY                = 'http_via';
    const VALIDATOR_REMOTE_ADDR_KEY             = 'remote_addr';

    /**
     * Configure and start session
     *
     * @param string $sessionName
     * @return $this
     */
    public function start($sessionName=null)
    {
        if (isset($_SESSION) && !$this->getSkipEmptySessionCheck()) {
            return $this;
        }

        // getSessionSaveMethod has to return correct version of handler in any case
        $moduleName = $this->getSessionSaveMethod();
        session_save_path($this->getSessionSavePath());
        session_module_name($moduleName);

        /**
         * lifetime:
         * Lifetime of the session cookie, defined in seconds.
         * path:
         * Path on the domain where the cookie will work. Use a single slash ('/') for all paths on the domain.
         * domain:
         * Cookie domain, for example 'www.php.net'. To make cookies visible on all subdomains then the domain must be prefixed with a dot like '.php.net'.
         * secure:
         * If TRUE cookie will only be sent over secure connections.
         * httponly:
         * If set to TRUE then PHP will attempt to send the httponly flag when setting the session cookie.
         */
        // session cookie params
        $cookieParams = array(
            'lifetime' => 3600,
            'path'     => '/',
            'domain'   => '',
            'secure'   => '',
            'httponly' => ''
        );

        if (!$cookieParams['httponly']) {
            unset($cookieParams['httponly']);
            if (!$cookieParams['secure']) {
                unset($cookieParams['secure']);
                if (!$cookieParams['domain']) {
                    unset($cookieParams['domain']);
                }
            }
        }

        call_user_func_array('session_set_cookie_params', $cookieParams);

        if (!empty($sessionName)) {
            $this->setSessionName($sessionName);
        }

        // potential custom logic for session id (ex. switching between hosts)
        $this->setSessionId();

        session_cache_limiter('nocache');

        session_start();

        return $this;
    }

    /**
     * Retrieve cookie object
     *
     * @return $this
     */
    public function getCookie()
    {
        return false;
    }


    /**
     * Init session with namespace
     *
     * @param string $namespace
     * @param string $sessionName
     * @return $this
     */
    public function init($namespace, $sessionName=null)
    {
        if (!isset($_SESSION)) {
            $this->start($sessionName);
        }
        if (!isset($_SESSION[$namespace])) {
            $_SESSION[$namespace] = array();
        }

        $this->_data = &$_SESSION[$namespace];

        $this->validate();

        return $this;
    }

    /**
     * Additional get data with clear mode
     *
     * @param string $key
     * @param bool $clear
     * @return mixed
     */
    public function getData($key='', $clear = false)
    {
        $data = parent::getData($key);
        if ($clear && isset($this->_data[$key])) {
            unset($this->_data[$key]);
        }
        return $data;
    }

    /**
     * Retrieve session Id
     *
     * @return string
     */
    public function getSessionId()
    {
        return session_id();
    }

    /**
     * Set custom session id
     *
     * @param string $id
     * @return $this
     */
    public function setSessionId($id=null)
    {
        if (!is_null($id) && preg_match('#^[0-9a-zA-Z,-]+$#', $id)) {
            session_id($id);
        }
        return $this;
    }

    /**
     * Retrieve session name
     *
     * @return string
     */
    public function getSessionName()
    {
        return session_name();
    }

    /**
     * Set session name
     *
     * @param string $name
     * @return $this
     */
    public function setSessionName($name)
    {
        session_name($name);
        return $this;
    }

    /**
     * Unset all data
     *
     * @return $this
     */
    public function unsetAll()
    {
        $this->unsetData();
        return $this;
    }

    /**
     * Alias for unsetAll
     *
     * @return $this
     */
    public function clear()
    {
        return $this->unsetAll();
    }

    /**
     * Retrieve session save method
     * Default files
     *
     * @return string
     */
    public function getSessionSaveMethod()
    {
        return 'files';
    }

    /**
     * Get sesssion save path
     *
     * @return string
     */
    public function getSessionSavePath()
    {
        return BP.DS.'var'.DS.'session';
    }

    /**
     * Use REMOTE_ADDR in validator key
     *
     * @return bool
     */
    public function useValidateRemoteAddr()
    {
        return true;
    }

    /**
     * Use HTTP_VIA in validator key
     *
     * @return bool
     */
    public function useValidateHttpVia()
    {
        return true;
    }

    /**
     * Use HTTP_X_FORWARDED_FOR in validator key
     *
     * @return bool
     */
    public function useValidateHttpXForwardedFor()
    {
        return true;
    }

    /**
     * Use HTTP_USER_AGENT in validator key
     *
     * @return bool
     */
    public function useValidateHttpUserAgent()
    {
        return false; //false to allow all user agent (mobile)
    }

    /**
     * Retrieve skip User Agent validation strings (Flash etc)
     *
     * @return array
     */
    public function getValidateHttpUserAgentSkip()
    {
        return array();
    }

    /**
     * Validate session
     *
     * @param string $namespace
     * @return $this
     */
    public function validate()
    {
        if (!isset($this->_data[self::VALIDATOR_KEY])) {
            $this->_data[self::VALIDATOR_KEY] = $this->getValidatorData();
        }
        else {
            if (!$this->_validate()) {
                if($this->getCookie()){
                    $this->getCookie()->delete(session_name());
                }
                // throw core session exception
                $this->clear();
                throw new \Exception('Not validated Session');
            }
        }

        return $this;
    }

    /**
     * Validate data
     *
     * @return bool
     */
    protected function _validate()
    {
        $sessionData = $this->_data[self::VALIDATOR_KEY];
        $validatorData = $this->getValidatorData();

        if ($this->useValidateRemoteAddr()
            && $sessionData[self::VALIDATOR_REMOTE_ADDR_KEY] != $validatorData[self::VALIDATOR_REMOTE_ADDR_KEY]) {
            return false;
        }
        if ($this->useValidateHttpVia()
            && $sessionData[self::VALIDATOR_HTTP_VIA_KEY] != $validatorData[self::VALIDATOR_HTTP_VIA_KEY]) {
            return false;
        }

        $sessionValidateHttpXForwardedForKey = $sessionData[self::VALIDATOR_HTTP_X_FORVARDED_FOR_KEY];
        $validatorValidateHttpXForwardedForKey = $validatorData[self::VALIDATOR_HTTP_X_FORVARDED_FOR_KEY];
        if ($this->useValidateHttpXForwardedFor()
            && $sessionValidateHttpXForwardedForKey != $validatorValidateHttpXForwardedForKey ) {
            return false;
        }
        if ($this->useValidateHttpUserAgent()
            && $sessionData[self::VALIDATOR_HTTP_USER_AGENT_KEY] != $validatorData[self::VALIDATOR_HTTP_USER_AGENT_KEY]
        ) {
            $userAgentValidated = $this->getValidateHttpUserAgentSkip();
            foreach ($userAgentValidated as $agent) {
                if (preg_match('/' . $agent . '/iu', $validatorData[self::VALIDATOR_HTTP_USER_AGENT_KEY])) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * Retrieve unique user data for validator
     *
     * @return array
     */
    public function getValidatorData()
    {
        $parts = array(
            self::VALIDATOR_REMOTE_ADDR_KEY             => '',
            self::VALIDATOR_HTTP_VIA_KEY                => '',
            self::VALIDATOR_HTTP_X_FORVARDED_FOR_KEY    => '',
            self::VALIDATOR_HTTP_USER_AGENT_KEY         => ''
        );

        // collect ip data
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $parts[self::VALIDATOR_REMOTE_ADDR_KEY] = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($_ENV['HTTP_VIA'])) {
            $parts[self::VALIDATOR_HTTP_VIA_KEY] = (string)$_ENV['HTTP_VIA'];
        }
        if (isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
            $parts[self::VALIDATOR_HTTP_X_FORVARDED_FOR_KEY] = (string)$_ENV['HTTP_X_FORWARDED_FOR'];
        }

        // collect user agent data
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $parts[self::VALIDATOR_HTTP_USER_AGENT_KEY] = (string)$_SERVER['HTTP_USER_AGENT'];
        }

        return $parts;
    }

    /**
     * Regenerate session Id
     * @return $this
     */
    public function regenerateSessionId()
    {
        session_regenerate_id(true);
        return $this;
    }
}
