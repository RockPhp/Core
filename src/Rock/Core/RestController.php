<?php

class Rock_Core_RestController
{

    public function handle()
    {
        $input = Rock_Core_Input::getInstance();
        $request = $input->getServer('REQUEST_METHOD');
        switch ($request) {
            case 'GET':
                $this->requestGet();
                break;
            case 'POST':
                $this->requestPost();
                break;
            case 'PUT':
                $this->requestPut();
                break;
            case 'DELETE':
                $this->requestDelete();
                break;
        }
    }

    protected function requestGet()
    {}

    protected function requestPost()
    {}

    protected function requestPut()
    {}

    protected function requestDelete()
    {}
}
