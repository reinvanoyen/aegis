<?php

function autoloader($classname)
{
    $classname = ltrim($classname, '\\');
    include_once __DIR__.'/../src/'.str_replace('\\', '/', $classname).'.php';
}

spl_autoload_register('autoloader');
