<?php

use \Aegis\Cache\File;

class FileTest extends PHPUnit_Framework_TestCase
{
	public function testLoadReadWrite()
	{
		$file = File::load('tests/cache/test/test.php');

		$file->delete();
		$this->assertEquals($file->read(), '');
		$file->write('something');
		$this->assertEquals($file->read(), 'something');
	}
}