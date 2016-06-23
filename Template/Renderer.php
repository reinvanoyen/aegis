<?php

require_once 'Lexer/Lexer.php';
require_once 'Parser/Parser.php';
require_once 'Compiler/Compiler.php';
require_once 'Template/Runtime.php';

class Renderer
{
	const TPL_DIR = 'templates/';
	const CACHE_DIR = 'cache/templates/';

	private $cacheFilename;

	public function render( $filename )
	{
		$this->cacheFilename = static::CACHE_DIR . urlencode( $filename ) . '.php';

		// Get string to render
		$string = file_get_contents( static::TPL_DIR . $filename );

		// Tokenize the string
		$lexer = new Lexer();
		$tokenStream = $lexer->tokenize( $string );

		// Parse the token stream to a node tree
		$parser = new Parser();
		$parsedTree = $parser->parse( $tokenStream );

		// Create the compiler
		$compiler = new Compiler( $parsedTree );

		// Run the code
		$compiler->run();

		file_put_contents( $this->cacheFilename, $compiler->compile() );

		require $this->cacheFilename;
	}
}