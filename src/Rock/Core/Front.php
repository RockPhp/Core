<?php

abstract class Rock_Core_Front
{

    private $ctrStr;

    private $methodStr;

    private static $view = null;

    private static $vendor = 'Rock';

    public static function getVendor()
    {
        return self::$vendor;
    }

    public static function setVendor($vendor)
    {
        self::$vendor = $vendor;
    }

    public static function getView()
    {
        return self::$view;
    }

    private function setFromUri(array $requestUri, $redirectStatus = '')
    {
        $ctrStrTmp = self::$vendor . '_Ctr_';
        $methodTmp = '';
        $ctrStrTmp .= empty($requestUri[1]) ? $this->ctrStr : $requestUri[1];
        $methodTmp = empty($requestUri[2]) ? $this->methodStr : $requestUri[2];
        $viewTmp = empty($requestUri[3]) ? self::$view : $requestUri[3];
        $this->ctrStr = class_exists($ctrStrTmp) ? $ctrStrTmp : $this->ctrStr;
        $reflection = new ReflectionClass($this->ctrStr);
        $methodTmp = $reflection->hasMethod($methodTmp) ? $methodTmp : $this->methodStr;
        $methodRef = $reflection->getMethod($methodTmp);
        $this->methodStr = $methodRef->isPublic() ? $methodTmp : $this->methodStr;
        if (Rock_Core_ViewLoader::checkDir($viewTmp)) {
            self::$view = $viewTmp;
        }
    }

    private function setInternalVars()
    {
        $input = Rock_Core_Input::getInstance();
        $redirectStatus = $input->getServer('REDIRECT_STATUS');
        $dirName = dirname($input->getServer('SCRIPT_NAME')) . '/';
        $requestUri = $input->getServer('REQUEST_URI');
        if (empty($requestUri)) {
            $requestUri = $input->getArgvFull();
        } else {
            $requestUri = preg_replace('/' . preg_quote($dirName, '/') . '/', '', $requestUri, 1);
            $requestUri = explode('/', $requestUri);
        }
        $this->setFromUri($requestUri, $redirectStatus);
    }

    public function __construct(Rock_Core_Route $route)
    {
        $this->ctrStr = $route->getController();
        $this->methodStr = $route->getMethod();
        $this->ctrStr = $route->getCtrStr();
        self::$vendor = $route->getVendor();
        self::$view = $route->getView();
        $this->setInternalVars();
    }

    protected function go()
    {
        $ctrStr = $this->ctrStr;
        $methodStr = $this->methodStr;
        $ctr = new $ctrStr();
        $ctr->$methodStr();
    }

    public function getCtrStr()
    {
        $ctrl = explode('_', $this->ctrStr);
        return $ctrl[2];
    }

    public function getMethodStr()
    {
        return $this->methodStr;
    }
}
