<?php
namespace Rock\Core;

use PHPUnit\Framework\TestCase;
use Some;

final class ViewLoaderTest extends TestCase
{

    private function getViewPath()
    {
        return dirname(__DIR__, 2) . '/view/';
    }

    /**
     *
     * @test
     */
    public function vwloader()
    {
        $vl = new \Rock_Core_ViewLoader($this->getViewPath());
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
        $ctrlIndex = new Some\Ctr\Index();
        $viewPath = $ctrlIndex->getViewPath();
        $this->assertEquals($this->getViewPath(), $viewPath);

        $ctrlIndex->handle();
        $viewResult = $ctrlIndex->getViewResult();
        $this->assertEquals('some index', $viewResult);
    }
}

