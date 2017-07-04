<?php

namespace Aegis\Cache;

use Aegis\AegisError;

class File implements CacheEntryInterface
{
    private $filename;
    private $timestamp;

    public function __construct($filename)
    {
        $this->filename = $filename;

        $dir = dirname($this->filename);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($this->filename)) {
            fopen($this->filename, 'a');
            $this->timestamp = 0;
            return;
        }

        $this->timestamp = filemtime($this->filename);
    }

    public function delete()
    {
        if (! @unlink($this->filename)) {
            throw new AegisError('Could not delete cache file');
        }
    }

    public function write($contents)
    {
        file_put_contents($this->filename, $contents);
        $this->timestamp = time();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function read()
    {
        $contents = @file_get_contents($this->filename);

        if ($contents !== false) {
            return $contents;
        }

        return '';
    }
}
