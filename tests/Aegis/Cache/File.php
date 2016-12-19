<?php

use \Aegis\Cache\File;

class FileTest extends PHPUnit_Framework_TestCase
{
	public function testLoadWriteRead()
	{
		$file = \Aegis\Cache\Filesystem::load('myid');
		$file->write('ok');

		$file02 = \Aegis\Cache\Filesystem::load('myid');
		$this->assertEquals($file02->read(), 'ok');

		$file03 = \Aegis\Cache\Filesystem::load('myid');
		$file03->write('something');

		$this->assertEquals($file->read(), 'something');
	}

	public function testLoadWriteReadWithOption()
	{
		$file = \Aegis\Cache\Filesystem::load('myid');
		$file->write('ok');

		$file02 = \Aegis\Cache\Filesystem::load('myid');
		$this->assertEquals($file02->read(), 'ok');

		$file03 = \Aegis\Cache\Filesystem::load('myid');
		$file03->write('something');

		$this->assertEquals($file->read(), 'something');
	}
}