<?php

namespace Aegis;

class NodeRegistry
{
    private static $registeredNodes = [];

    public static function register($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $classname) {
                static::register($classname);
            }
        } else {
            static::$registeredNodes[] = $mixed;
        }
    }

    public static function getNodes()
    {
        return static::$registeredNodes;
    }
}
