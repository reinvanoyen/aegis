<?php

namespace Aegis\Node;

use Aegis\Token;

class StringNode extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_STRING ) ) {
			
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();
		}
	}

	public function compile( $compiler )
	{
		$compiler->write( '\'' . $this->value . '\'' );
	}
}