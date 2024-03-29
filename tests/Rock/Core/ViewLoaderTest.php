<?php

final class ViewLoaderTest extends PHPUnit_Framework_TestCase
{

    private function getViewPath()
    {
        return dirname(dirname(dirname(__FILE__))) . '/view/';
    }

    /**
     *
     * @test
     */
    public function vwloader()
    {
        $vl = new Rock_Core_ViewLoader($this->getViewPath());
        $content = $vl->load('hello', array(), false);
        $this->assertEquals('something', $content);

        $content = $vl->load('somedata', array(
            'myvar' => 'is here'
        ), false);
        $this->assertEquals('is here', $content);
    }

    /**
     *
     * @test
     */
    public function someFront()
    {
        $ctrlIndex = new Some_Ctr_Index();
        $viewPath = $ctrlIndex->getViewPath();
        $this->assertEquals($this->getViewPath(), $viewPath);

        $ctrlIndex->handle();
        $viewResult = $ctrlIndex->getViewResult();
        $this->assertEquals('some index', $viewResult);
    }
}

