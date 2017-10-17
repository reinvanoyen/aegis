<?php

namespace Aegis\Cache;

use Aegis\AegisError;

/**
 * Class File
 * @package Aegis\Cache
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class File implements CacheEntryInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * File constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
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

    /**
     * Deletes the file from the filesystem
     *
     * @throws AegisError
     * @return void
     */
    public function delete() : void
    {
        if (! @unlink($this->filename)) {
            throw new AegisError('Could not delete cache file');
        }
    }

    /**
     * Writes contents to the file
     *
     * @param string $contents
     */
    public function write(string $contents) : void
    {
        file_put_contents($this->filename, $contents);
        $this->timestamp = time();
    }

    /**
     * Gets the filename
     *
     * @return string
     */
    public function getFilename() : string
    {
        return $this->filename;
    }

    /**
     * Gets the timestamp of last modification of the file
     *
     * @return int
     */
    public function getTimestamp() : int
    {
        return $this->timestamp;
    }

    /**
     * Gets the contents of the file
     *
     * @return string
     */
    public function read() : string
    {
        $contents = @file_get_contents($this->filename);

        if ($contents !== false) {
            return $contents;
        }

        return '';
    }
}
