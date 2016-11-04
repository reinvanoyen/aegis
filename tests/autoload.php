<?php

include_once __DIR__ . '/../vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('Aegis\\', __DIR__ . '/../lib/', true);
$classLoader->register();

/*
function autoloader($classname)
{
    $classname = ltrim($classname, '\\');
    include_once 'lib/'.str_replace('\\', '/', $classname).'.php';
}

spl_autoload_register('autoloader');
*/
