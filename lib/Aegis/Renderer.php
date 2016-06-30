<?php

namespace Aegis;

class Renderer
{
	public static $templateExtension = 'tpl';
	public static $templateDirectory = 'templates/';
	public static $cacheDirectory = 'cache/templates/';

	private $cacheFilename;

	private function generateCacheFilename( $filename, $extension = 'php', $prefix = NULL )
	{
		return static::$cacheDirectory . ( $prefix ? $prefix . '/' : NULL ) . urlencode( $filename ) . '.' . $extension;
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
		$compiler = $this->getCompiler( $filename );

		$this->cacheFilename = $this->generateCacheFilename( $filename );

		// Compile and save
		file_put_contents( $this->cacheFilename, $compiler->compile() );

		// Execute
		$this->execute();
	}

	public function renderHead( $filename )
	{
		$compiler = $this->getCompiler( $filename );

		$this->cacheFilename = $this->generateCacheFilename( $filename, 'php', 'head' );

		// Compile and save
		$compiler->compile();
		file_put_contents( $this->cacheFilename, $compiler->getHead() );

		// Execute
		$this->execute();
	}

	public function renderBody( $filename )
	{
		$compiler = $this->getCompiler( $filename );

		$this->cacheFilename = $this->generateCacheFilename( $filename, 'php', 'body' );

		// Compile and save
		$compiler->compile();
		file_put_contents( $this->cacheFilename, $compiler->getBody() );

		// Execute
		$this->execute();
	}

	private function execute()
	{
		require $this->cacheFilename;
	}
}