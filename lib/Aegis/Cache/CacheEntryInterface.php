<?php

namespace Aegis\Cache;

interface CacheEntryInterface
{
    public function read();
    public function write($contents);
    public function delete();
    public function getTimestamp();
}
