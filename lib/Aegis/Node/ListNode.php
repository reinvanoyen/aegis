<?php

namespace Aegis\Node;

use Aegis\Token;

class ListNode extends \Aegis\Node
{
	public static function parse( $parser )
	{
		if( $parser->accept( Token::T_SYMBOL, '[' ) ) {

			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			ArgumentListNode::parse( $parser );

			$parser->expect( Token::T_SYMBOL, ']' );
			$parser->advance();
			$parser->traverseDown();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		$compiler->write( '[' );

		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}

		$compiler->write( ']' );
	}
}