<?php

class Rock_Core_ViewLoader
{

    private static $instance = null;

    private static $pathRock = '';

    private $vars = array();

    /**
     * Singleton
     *
     * @return Rock_Core_ViewLoader
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        self::$pathRock = dirname(dirname(__FILE__)) . '/';
    }

    private static function isSsl()
    {
        $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : '';
        $httpPort = $_SERVER['SERVER_PORT'];
        if (! empty($https)) {
            if (strtolower($https) == 'on') {
                return true;
            }
            if ($https == '1') {
                return true;
            }
        } elseif (! empty($httpPort) && ($httpPort == '443')) {
            return true;
        }
        return false;
    }

    private static function getHttpXHost()
    {
        if (empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        return $_SERVER['HTTP_X_FORWARDED_HOST'];
    }

    public static function getUrl($withFile = false)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('/', $uri);
        $uriTmp = '';
        $phpFile = '';
        foreach ($uri as $v) {
            if (substr_count($v, '.php')) {
                $phpFile = $v;
                break;
            }
            $uriTmp .= $v . '/';
        }
        $formUrl = 'http://';
        if (self::isSsl()) {
            $formUrl = 'https://';
        }
        $formUrl .= self::getHttpXHost();
        $formUrl .= $uriTmp;
        if ($withFile) {
            $formUrl .= $phpFile;
        }
        return $formUrl;
    }

    public static function getFormUrl($controller = 'Index', $method = 'handle')
    {
        $input = Rock_Core_Input::getInstance();
        $uri = $input->getServer('REQUEST_URI');
        $uri = explode('/', $uri);
        $uriTmp = '';
        foreach ($uri as $v) {
            $uriTmp .= $v . '/';
            if (substr_count($v, '.php')) {
                break;
            }
        }
        $formUrl = 'http://';
        if (self::isSsl()) {
            $formUrl = 'https://';
        }
        $formUrl .= self::getHttpXHost();
        $formUrl .= $uriTmp;
        $formUrl .= $controller . '/' . $method . '/';
        return $formUrl;
    }

    public static function getPathRock()
    {
        return self::$pathRock;
    }

    private function detectBrowser()
    {
        $browser = Rock_Core_Front::getView();
        if ($browser === null) {
            $browser = 'w3c';
            if (Rock_Core_Input::isShell()) {
                $browser = 'shell';
            }
        }
        return $browser;
    }

    public static function checkDir($browser = 'w3c')
    {
        $vendor = Rock_Core_Front::getVendor();
        $dirName = $vendor . '/view/' . $browser;
        return is_dir($dirName);
    }

    private function setData(array $data)
    {
        foreach ($data as $k => $v) {
            if (! isset($this->vars[$k])) {
                $this->setVar($k, $v);
            }
        }
    }

    public function load($viewPath, array $data = array(), $echo = true)
    {
        $pathRock = self::$pathRock;
        $this->setData($data);
        $browser = $this->detectBrowser();
        $vendor = Rock_Core_Front::getVendor();
        $filename = $pathRock . $vendor . '/view/' . $browser . '/' . $viewPath . '.php';
        ob_start();
        if (is_file($filename)) {
            include $filename;
        } else {
            throw new Exception('File ' . $filename . ' not found.');
        }
        $html = ob_get_clean();
        if ($echo) {
            echo $html;
            if (! Rock_Core_Input::isShell() && ! Rock_Core_Input::isWc()) {
                ob_flush();
                flush();
            }
        }
        return $html;
    }

    public function setVar($name, $var)
    {
        $this->vars[$name] = $var;
    }

    public function getVar($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        return '';
    }

    public function getVars()
    {
        return $this->vars;
    }
}
