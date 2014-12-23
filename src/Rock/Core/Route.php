<?php

class Rock_Core_Route
{

    private $vendor = 'Sample';

    private $controller = 'Index';

    private $method = 'handle';

    private $view = null;

    public function getCtrStr()
    {
        return $this->vendor . '_Ctr_' . $this->controller;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($vendor)
    {
        if (! empty($vendor)) {
            $this->vendor = $vendor;
        }
        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        if (! empty($controller)) {
            $this->controller = $controller;
        }
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        if (! empty($method)) {
            $this->method = $method;
        }
        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        if(!empty($view)){
            $this->view = $view;
        }
        return $this;
    }
}
