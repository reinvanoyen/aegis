<?php

namespace Aegis;

class Renderer
{
	public static $debug = TRUE;

	public static $templateExtension = 'tpl';
	public static $templateDirectory = 'templates/';
	public static $cacheDirectory = 'cache/templates/';

	private $cacheFilename;
	private $srcFilename;

	private function generateCacheFilename( $filename, $extension = 'php', $prefix = NULL )
	{
		return static::$cacheDirectory . ( $prefix ? $prefix . '/' : NULL ) . urlencode( $filename ) . '.' . $extension;
	}

	private function generateSourceFilename( $filename )
	{
		return static::$templateDirectory . $filename . '.' . static::$templateExtension;
	}

	private function shouldRecompile()
	{
		if( ! file_exists( $this->cacheFilename ) || filemtime( $this->cacheFilename ) <= filemtime( $this->srcFilename ) || static::$debug ) {

			if( !file_exists( static::$cacheDirectory ) ) {

				mkdir( static::$cacheDirectory, 0777, TRUE );
			}

			return TRUE;
		}

		return FALSE;
	}

	private function getCompiler( $filename )
	{
		// Get string to render
		$string = file_get_contents( static::$templateDirectory . $filename . '.' . static::$templateExtension );

		// Tokenize the string
		$lexer = new Lexer();
		$tokenStream = $lexer->tokenize( $string );

		// Parse the token stream to a node tree
		$parser = new Parser();
		$parsedTree = $parser->parse( $tokenStream );

		// Create the compiler
		$compiler = new Compiler( $parsedTree );

		return $compiler;
	}

	public function render( $filename )
	{
		$this->cacheFilename = $this->generateCacheFilename( $filename );
		$this->srcFilename = $this->generateSourceFilename( $filename );

		if( $this->shouldRecompile() ) {

			$compiler = $this->getCompiler( $filename );
			file_put_contents( $this->cacheFilename, $compiler->compile() );
		}

		// Execute
		return $this->execute();
	}

	public function renderHead( $filename )
	{
		$this->cacheFilename = $this->generateCacheFilename( $filename, 'php', 'head' );
		$this->srcFilename = $this->generateSourceFilename( $filename );

		if( $this->shouldRecompile() ) {

			$compiler = $this->getCompiler( $filename );
			// Compile and save
			$compiler->compile();
			file_put_contents( $this->cacheFilename, $compiler->getHead() );
		}

		// Execute
		return $this->execute();
	}

	public function renderBody( $filename )
	{
		$this->cacheFilename = $this->generateCacheFilename( $filename, 'php', 'body' );
		$this->srcFilename = $this->generateSourceFilename( $filename );

		if( $this->shouldRecompile() ) {

			$compiler = $this->getCompiler( $filename );
			// Compile and save
			$compiler->compile();
			file_put_contents( $this->cacheFilename, $compiler->getBody() );
		}

		// Execute
		return $this->execute();
	}

	private function execute()
	{
		ob_start();
		require $this->cacheFilename;
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
}