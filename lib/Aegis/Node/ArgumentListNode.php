<?php

namespace Aegis\Node;

use Aegis\Token;

class ArgumentListNode extends Node
{
	public static function parse( $parser )
	{
		$parser->insert( new static() );
		$parser->traverseUp();

		ExpressionNode::parse( $parser );

		if( $parser->skip( Token::T_SYMBOL, ',' ) ) {

			self::parse( $parser );
		}

		$parser->traverseDown();
	}

	public function compile( $compiler )
	{
		$i = 0;

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );

			$i++;

			if( $i < count( $this->getChildren() ) ) {

				$compiler->write( ', ' );
			}
		}
	}
}