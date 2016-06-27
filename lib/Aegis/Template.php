<?php

namespace Aegis;

class Template
{
	public static $template_extension = 'tpl';
	public static $template_directory = 'templates/';
	public static $cache_directory = 'cache/templates/';

	private $cacheFilename;

	private $variables = [];
	private $blocks = [];

	public function render( $filename )
	{
		$this->cacheFilename = static::$cache_directory . urlencode( $filename ) . '.php';

		// Get string to render
		$string = file_get_contents( static::$template_directory . $filename . '.' . static::$template_extension );

		// Tokenize the string
		$lexer = new Lexer();
		$tokenStream = $lexer->tokenize( $string );

		// Parse the token stream to a node tree
		$parser = new Parser();
		$parsedTree = $parser->parse( $tokenStream );

		// Create the compiler
		$compiler = new Compiler( $parsedTree );

		// Compile and save
		file_put_contents( $this->cacheFilename, $compiler->compile() );

		// Execute
		$this->execute();
	}

	public function renderHead( $filename )
	{
		$this->cacheFilename = static::$cache_directory . 'head/' . urlencode( $filename ) . '.php';

		// Get string to render
		$string = file_get_contents( static::$template_directory . $filename . '.' . static::$template_extension );

		// Tokenize the string
		$lexer = new Lexer();
		$tokenStream = $lexer->tokenize( $string );

		// Parse the token stream to a node tree
		$parser = new Parser();
		$parsedTree = $parser->parse( $tokenStream );

		// Create the compiler
		$compiler = new Compiler( $parsedTree );

		// Compile
		$compiler->compile();

		// Compile and save
		file_put_contents( $this->cacheFilename, $compiler->getHead() );

		// Execute
		$this->execute();
	}

	public function renderBody( $filename )
	{
		$this->cacheFilename = static::$cache_directory . 'body/' . urlencode( $filename ) . '.php';

		// Get string to render
		$string = file_get_contents( static::$template_directory . $filename . '.' . static::$template_extension );

		// Tokenize the string
		$lexer = new Lexer();
		$tokenStream = $lexer->tokenize( $string );

		// Parse the token stream to a node tree
		$parser = new Parser();
		$parsedTree = $parser->parse( $tokenStream );

		// Create the compiler
		$compiler = new Compiler( $parsedTree );

		// Compile
		$compiler->compile();

		// Compile and save
		file_put_contents( $this->cacheFilename, $compiler->getBody() );

		// Execute
		$this->execute();
	}

	private function execute()
	{
		require $this->cacheFilename;
	}

	// Runtime

	public function __get( $k )
	{
		return $this->variables[ $k ];
	}

	public function __set( $k, $v )
	{
		$this->variables[ $k ] = $v;
	}

	public function setBlock( $id, $callable )
	{
		$this->blocks[ $id ] = [ $callable ];
	}

	public function appendBlock( $id, $callable )
	{
		$this->blocks[ $id ][] = $callable;
	}

	public function prependBlock( $id, $callable )
	{
		array_unshift( $this->blocks[ $id ], $callable );
	}

	public function getBlock( $id )
	{
		foreach( $this->blocks[ $id ] as $callable )
		{
			$callable();
		}
	}
}