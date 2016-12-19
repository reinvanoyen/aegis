<?php

namespace Aegis\Cache;

use Aegis\AegisError;

class File
{
	private $id;
	private $filename;

	public function __construct($id)
	{
		$this->id = $id;
		$this->filename = Filesystem::generateCachedFilename($id);

		$dir = dirname($this->filename);
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		if (!file_exists($this->filename)) {
			fopen($this->filename, 'a');
		}
	}

	public function isExpired()
	{
		return ! file_exists($this->filename) || filemtime($this->filename) <= filemtime($this->id);
	}

	public function delete()
	{
		if( ! @unlink($this->filename) ) {
			throw new AegisError('Could not delete cache file');
		}
	}

	public function write($content)
	{
		file_put_contents($this->filename, $content);
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