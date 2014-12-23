<?php

class Rock_Core_Session
{

    private static $instance;

    private function __construct()
    {
        if (! isset($_SESSION)) {
            session_start();
        }
    }

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function killSession()
    {
        $this->clearSession();
        session_destroy();
    }

    public function clearSession()
    {
        $keys = array_keys($_SESSION);
        foreach ($keys as $key) {
            unset($_SESSION[$key]);
        }
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function unsetSession($key)
    {
        unset($_SESSION[$key]);
    }

    public function getSession($key)
    {
        $session = isset($_SESSION[$key]) ? $_SESSION[$key] : "";
        return $session;
    }
}
