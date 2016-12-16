<?php

namespace Aegis\Cache;

use Aegis\AegisError;

class File
{
	private $filename;

	private function __construct($filename)
	{
		$this->filename = $filename;
	}

	public function delete()
	{
		if( ! unlink($this->filename) ) {
			throw new AegisError('Could not delete file');
		}
	}

	public function write($content)
	{
		file_put_contents($this->filename, $content);
	}

	public function read()
	{
		$contents = @file_get_contents($this->filename);

		if ( $contents !== false ) {
			return $contents;
		}

		return '';
	}

	public static function load($filename)
	{
		$dir = dirname($filename);

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		return new static($filename);
	}
}