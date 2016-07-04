<?php

namespace Aegis\Node;

use Aegis\Token;

class VariableNode extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_VAR ) ) {

			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		$compiler->write( '$this->' . str_replace( '.', '->', $this->name ) );
	}
}