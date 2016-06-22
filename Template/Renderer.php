<?php

require_once 'Lexer/Lexer.php';
require_once 'Parser/Parser.php';
require_once 'Compiler/Compiler.php';
require_once 'Template/Runtime.php';

class Renderer
{
	public static $runtime;

	public static $debug = TRUE;

	public static $tpl_dir = 'templates/';
	public static $cache_dir = 'cache/templates/';

	private $cached_filename;

	private $variables = [];
	private $blocks = [];

	public function __construct()
	{
		self::$runtime = new Runtime();
	}

	public function getCompiled( $filename )
	{
		// Tokenize the file
		$lexer = new Lexer();
		$stream = $lexer->tokenize( file_get_contents( $filename ) );

		// Parse the stream
		$parser = new Parser();
		$parsed = $parser->parse( $stream );

		// Compile the parsed node tree
		$compiler = new Compiler();
		$compiled = $compiler->compile( $parsed );

		return $compiled;
	}

	public function render( $filename )
	{
		$filename = static::$tpl_dir . $filename;

		$this->cached_filename = static::$cache_dir . urlencode( $filename ) . '.php';

		if( ! file_exists( $this->cached_filename ) || filemtime( $this->cached_filename ) <= filemtime( $filename ) || self::$debug )
		{
			if( ! file_exists( static::$cache_dir ) )
			{
				// If the cache directory doesn't exist, create it
				mkdir( static::$cache_dir, 0777, TRUE );
			}

			// Save the compiled template
			file_put_contents( $this->cached_filename, $this->getCompiled( $filename ) );
		}

		$this->execute();
	}

	private function execute()
	{
		if( ! $this->cached_filename )
		{
			echo 'TEMPLATE error';
		}

		require $this->cached_filename;
	}

	// Runtime functions

	public function __set( $k, $v )
	{
		self::$runtime->setVariable( $k, $v );
	}

	public function __get( $k )
	{
		return self::$runtime->variables[ $k ];
	}
}