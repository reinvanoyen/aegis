<?php

require_once 'Lexer/Lexer.php';
require_once 'Parser/Parser.php';
require_once 'Compiler/Compiler.php';
require_once 'Template/Runtime.php';

class Renderer
{
	public static $runtime;
	public static $filename;

	public static $debug = FALSE;

	public static $tpl_dir = 'templates/';
	public static $cache_dir = 'cache/templates/';

	private $cached_filename;

	private $parsed;
	private $compiler;

	public function __construct( $runtime = NULL )
	{
		self::$runtime = ( $runtime ?: new Runtime() );
	}

	public function parse( $filename )
	{
		static::$filename = $filename;

		// Tokenize the file
		$lexer = new Lexer();
		$stream = $lexer->tokenize( file_get_contents( static::$tpl_dir . $filename ) );

		// Parse the stream
		$parser = new Parser();
		$this->parsed = $parser->parse( $stream );
	}

	public function run()
	{
		if( ! $this->parsed )
		{
			throw new Exception( 'Parse before compiling!' );
		}

		// Create the compiler
		$this->compiler = new Compiler( $this->parsed );

		// Run the nodes
		$this->compiler->run();
	}

	public function compile()
	{
		if( ! $this->compiler )
		{
			throw new Exception( 'Run before compiling!' );
		}

		return $this->compiler->compile();
	}

	public function render( $filename )
	{
		$src = static::$tpl_dir . $filename;

		$this->cached_filename = static::$cache_dir . urlencode( $filename ) . '.php';

		// Parse and run
		$this->parse( $filename );
		$this->run();

		if( ! file_exists( $this->cached_filename ) || filemtime( $this->cached_filename ) <= filemtime( $src ) || self::$debug )
		{
			if( ! file_exists( static::$cache_dir ) )
			{
				// If the cache directory doesn't exist, create it
				mkdir( static::$cache_dir, 0777, TRUE );
			}

			file_put_contents( $this->cached_filename, $this->compile() );
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

	public function getBlock( $id )
	{
		return self::$runtime->getBlock( $id );
	}
}