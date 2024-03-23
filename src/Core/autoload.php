<?php
function autoloader($class)
{
    $class=preg_replace('/^App\\\/', 'src/', $class);
    $class=preg_replace('/\\\/', '/', $class);
    include dirname(__DIR__, 2)
        . '/'
        . $class
        . '.php';
}

spl_autoload_register('autoloader');

