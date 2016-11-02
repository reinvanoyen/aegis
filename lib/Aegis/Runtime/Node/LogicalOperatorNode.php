<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;
use Aegis\Node;

class LogicalOperatorNode extends Node
{
	private $type;

	public function __construct($type)
	{
		$this->type = $type;
	}

	public static function parse($parser)
	{
		if (
			$parser->accept(Token::T_IDENT, 'not') ||
			$parser->accept(Token::T_IDENT, 'or') ||
			$parser->accept(Token::T_IDENT, 'and') ||
			$parser->accept(Token::T_IDENT, 'equals')
		) {
			$parser->insert(new static($parser->getCurrentToken()->getValue()));
			$parser->advance();

			return true;
		}

		return false;
	}

	public function compile($compiler)
	{
		if ($this->type === 'not') {
			$compiler->write(' ! ');
		} else if( $this->type === 'or' ) {
			$compiler->write(' || ');
		} else if( $this->type === 'and' ) {
			$compiler->write(' && ');
		} else if( $this->type === 'equals' ) {
			$compiler->write(' === ');
		}
	}
}