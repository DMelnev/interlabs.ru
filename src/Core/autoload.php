<?php
function autoloader($class)
{
    include dirname(__DIR__, 2)
        . '/'
        . preg_replace(['/^App\\\/', '/\\\/'], ['src/', '/'], $class)
        . '.php';
}

spl_autoload_register('autoloader');

