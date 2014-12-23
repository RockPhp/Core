<?php

class Rock_Core_Input
{

    private static $instance = null;

    private $argShell = array();

    private $argWc = array();

    private $argv = array();

    /**
     * Singleton
     *
     * @return Rock_Core_Input
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function isWc()
    {
        if (Rock_Core_Front::getView() == 'ws') {
            return true;
        }
        return false;
    }

    public static function isShell()
    {
        if (php_sapi_name() == 'cli') {
            return true;
        }
        return false;
    }

    private function setArgv()
    {
        $arrShell = array();
        foreach ($this->argv as $k => $v) {
            if (substr_count($v, '--') > 0) {
                $v = str_replace('--', '', $v);
                $arrShell[$v] = $this->argv[$k + 1];
            }
        }
        $this->argShell = $arrShell;
        if (isset($this->argShell['SHELL_VARS'])) {
            if (! is_file($this->argShell['SHELL_VARS'])) {
                throw new Exception($this->argShell['SHELL_VARS']);
            }
            $handle = fopen($this->argShell['SHELL_VARS'], 'r');
            $json = fread($handle, 8192);
            fclose($handle);
            $array = json_decode($json);
            if (isset($array->ENV)) {
                $_ENV = get_object_vars($array->ENV);
            }
            if (isset($array->GET)) {
                $_GET = get_object_vars($array->GET);
            }
            if (isset($array->POST)) {
                $_POST = get_object_vars($array->POST);
            }
            if (isset($array->COOKIE)) {
                $_COOKIE = get_object_vars($array->COOKIE);
            }
            if (isset($array->SERVER)) {
                $_SERVER = array_merge($_SERVER, get_object_vars($array->SERVER));
            }
            if (isset($array->FILES)) {
                $_FILES = get_object_vars($array->FILES);
            }
            if (isset($array->SESSION)) {
                $_SESSION = get_object_vars($array->SESSION);
            }
            $_REQUEST = array_merge($_ENV, $_GET, $_POST, $_COOKIE);
        }
    }

    private function __construct()
    {
        if (self::isShell()) {
            global $argv;
            $this->argv = $argv;
            $this->setArgv();
        }
        if (self::isWc()) {
            $this->setArgWc();
        }
    }

    private function setArgWc()
    {
        $xml = file_get_contents('php://input', 'r');
        if (! empty($xml)) {
            $xml = new SimpleXMLElement($xml);
            $xml->registerXPathNamespace('rock', Rock_Core_ViewLoader::getUrl());
            $res = $xml->xpath('//rock:*');
            foreach ($res as $item) {
                $children = $item->children();
                foreach ($children as $child) {
                    $childName = $child->getName();
                    $childValue = $item->$childName;
                    $this->argWc[$childName] = (string) $childValue;
                }
            }
        }
    }

    public function getArgvFull()
    {
        return $this->argv;
    }

    public function getServer($key)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : '';
    }

    public function getRequest($key)
    {
        $request = isset($_REQUEST[$key]) ? $_REQUEST[$key] : "";
        if (empty($request)) {
            $request = $this->getExtArg($key);
        }
        return $request;
    }

    public function getPost($key)
    {
        $post = isset($_POST[$key]) ? $_POST[$key] : "";
        if (empty($post)) {
            $post = $this->getExtArg($key);
        }
        return $post;
    }

    public function getFile($key)
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        return array(
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'error' => 4,
            'size' => 0
        );
    }

    public function getGet($key)
    {
        $get = isset($_GET[$key]) ? $_GET[$key] : "";
        if (empty($get)) {
            $get = $this->getExtArg($key);
        }
        return $get;
    }

    private function getExtArg($key)
    {
        if (self::isWc()) {
            return $this->getWcArg($key);
        }
        if (self::isShell()) {
            return $this->getShellArg($key);
        }
    }

    private function getShellArg($key)
    {
        return isset($this->argShell[$key]) ? $this->argShell[$key] : "";
    }

    private function getWcArg($key)
    {
        return isset($this->argWc[$key]) ? $this->argWc[$key] : "";
    }

    public function getHttpRaw()
    {
        $handle = fopen('php://input', 'r');
        $raw = fread($handle, 1024000);
        fclose($handle);
        return $raw;
    }
}
