<?php

namespace Aegis\Cache;

/**
 * Class Filesystem
 * @package Aegis\Cache
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Filesystem implements CacheInterface
{
    const CACHE_EXTENSION = 'php';
    const CACHE_DIR = 'cache/';

    /**
     * @var CacheEntryInterface[]
     */
    private static $files = [];

    /**
     * Gets the cache file from the filesystem
     *
     * @param $id
     * @param null $subId
     * @return CacheEntryInterface
     */
    public static function load($id, $subId = null) : CacheEntryInterface
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
