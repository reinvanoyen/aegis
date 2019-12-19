<?php

namespace Aegis\Contracts;

/**
 * Interface LoaderInterface
 * @package Aegis\Contracts
 */
interface LoaderInterface
{
    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string;

    /**
     * @param string $key
     * @return bool
     */
    public function isCached(string $key): bool;

    /**
     * @param string $key
     * @return string
     */
    public function getCache(string $key): string;

    /**
     * @param string $key
     * @param string $contents
     * @return mixed
     */
    public function setCache(string $key, string $contents);

    /**
     * @param string $key
     * @return mixed
     */
    public function getCacheKey(string $key);
}