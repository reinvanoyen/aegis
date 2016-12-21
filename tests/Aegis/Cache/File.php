<?php

use \Aegis\Cache\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    private $file;

    public function setup()
    {
        $this->file = \Aegis\Cache\Filesystem::load('myid', 'sub');
    }

    public function testLoad()
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    public function testWrite()
    {
        $contents = 'ok';

        $this->file->write($contents);
        $this->assertEquals($this->file->read(), $contents);
    }
}
