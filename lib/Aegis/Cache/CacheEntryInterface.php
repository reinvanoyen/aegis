<?php

namespace Aegis\Cache;

/**
 * Interface CacheEntryInterface
 * @package Aegis\Cache
 */
interface CacheEntryInterface
{
    /**
     * Should get the contents of the cache entry
     *
     * @return string
     */
    public function read() : string;

    /**
     * Should write contents to the cache entry
     *
     * @param string $contents
     */
    public function write(string $contents) : void;

    /**
     * Should delete the cache entry
     *
     * @return void
     */
    public function delete() : void;

    /**
     * Should get the timestamp of the last update of the cache entry
     *
     * @return int
     */
    public function getTimestamp() : int;
}
