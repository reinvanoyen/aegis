<?php

namespace Aegis\Cache;

/**
 * Interface CacheInterface
 * @package Aegis\Cache
 */
interface CacheInterface
{
    /**
     * Gets the cache entry
     *
     * @param $id
     * @param null $subentry
     * @return CacheEntryInterface
     */
    public static function load($id, $subentry = null) : CacheEntryInterface;
}
