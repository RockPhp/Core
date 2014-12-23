<?php

interface Rock_Core_IRestController
{

    public function requestGet();

    public function requestPost();

    public function requestPut();

    public function requestDelete();
}
