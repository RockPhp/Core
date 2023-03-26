<?php
namespace Some\Ctr;

class Index implements \Rock_Core_IController
{

    private $viewResult;

    public function getViewPath()
    {
        return dirname(__DIR__, 3) . '/tests/view/';
    }

    public function handle()
    {
        $vl = new \Rock_Core_ViewLoader($this->getViewPath());
        $this->viewResult = $vl->load('index', array(), false);
    }

    public function getViewResult()
    {
        return $this->viewResult;
    }
}

