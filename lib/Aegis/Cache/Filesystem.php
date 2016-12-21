<?php

namespace Aegis\Cache;

class Filesystem implements CacheInterface
{
    const CACHE_EXTENSION = 'php';
    const CACHE_DIR = 'cache/';

    private static $files = [];

    public static function load($id, $subId = null)
    {
        $storageId = $id;

        if ($subId) {
            $storageId .= '[' . $subId . ']';
        }

        if (!isset(self::$files[$storageId])) {
            $filename = static::CACHE_DIR . ($subId ? $subId . '/' : null) . urlencode($id) . '.' . static::CACHE_EXTENSION;
            self::$files[$storageId] = new File($filename);
        }

        return self::$files[$storageId];
    }
}
