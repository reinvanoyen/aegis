<?php

namespace Aegis\Node;

use Aegis\Token;

class NumberNode extends \Aegis\Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_NUMBER ) ) {

			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		$compiler->write( $this->getValue() );
	}
}
