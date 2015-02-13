<?php

class Rock_Core_AutoLoad
{

    public static function loadClass($className)
    {
        $fileName = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        $arrPath = explode("_", $className);
        $fileName .= DIRECTORY_SEPARATOR . strtolower($arrPath[0]);
        $fileName .= DIRECTORY_SEPARATOR . strtolower($arrPath[1]);
        $fileName .= DIRECTORY_SEPARATOR . 'src';
        $fileName .= DIRECTORY_SEPARATOR;
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (is_file($fileName)) {
            require_once $fileName;
        }
    }
}

spl_autoload_register('Rock_Core_AutoLoad::loadClass');
if (function_exists('__autoload')) {
    spl_autoload_register('__autoload');
}
