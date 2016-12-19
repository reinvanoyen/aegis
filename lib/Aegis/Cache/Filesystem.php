<?php

namespace Aegis\Cache;

class Filesystem implements CacheInterface
{
	const CACHE_EXTENSION = 'php';
	const CACHE_DIR = 'cache/';

	private static $files = [];

	public static function load($id)
	{
		if (!isset(self::$files[$id])) {

			self::$files[$id] = new File($id);
		}

		return self::$files[$id];
	}

	public static function generateCachedFilename($id)
	{
		return static::CACHE_DIR . urlencode($id) . '.' . static::CACHE_EXTENSION;
	}
}