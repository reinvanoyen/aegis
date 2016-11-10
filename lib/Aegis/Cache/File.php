<?php

namespace Aegis\Cache;

class File
{
	private $filename;

	private function __construct($filename)
	{
		$this->filename = $filename;
	}

	public function write($content)
	{
		file_put_contents($this->filename, $content);
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