<?php

require_once 'Lexer/Lexer.php';
require_once 'Parser/Parser.php';
require_once 'Compiler/Compiler.php';

class Template
{
	private $variables = [];
	private $cached_filename;

	public function __set( $k, $v )
	{
		$this->variables[ $k ] = $v;
	}

	public function __get( $k )
	{
		return $this->variables[ $k ];
	}

	public function get( $filename )
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
		$this->cached_filename = 'cache/' . urlencode( $filename ) . '.php';

		// Save the data
		file_put_contents( $this->cached_filename, $this->get( $filename ) );

		$this->execute();
	}

	public function execute()
	{
		if( ! $this->cached_filename )
		{
			echo 'TEMPLATE error';
		}

		require $this->cached_filename;
	}
}