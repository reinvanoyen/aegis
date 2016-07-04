<?php

namespace Aegis\Node;

use Aegis\Token;

class ExpressionNode extends Node
{
	public static function parse( $parser )
	{
		if(
			$parser->accept( Token::T_VAR ) ||
			$parser->accept( Token::T_STRING ) ||
			$parser->accept( Token::T_NUMBER ) ||
			( $parser->accept( Token::T_IDENT ) && $parser->acceptNext( Token::T_SYMBOL, '(' ) )
		) {
			if( ! $parser->getScope() instanceof ExpressionNode ) {

				// Insert the expression and move inside
				$parser->insert( new static() );
				$parser->traverseUp();
			}

			StringNode::parse( $parser );
			NumberNode::parse( $parser );
			VariableNode::parse( $parser );
			FunctionCallNode::parse( $parser );

			if( Operator::parse( $parser ) ) {

				self::parse( $parser );

			} else {
				
				$parser->traverseDown();
			}

			return TRUE;
		}

		return FALSE;
	}

	public function compile( $compiler )
	{
		foreach( $this->getChildren() as $c ) {

			$c->compile( $compiler );
		}
	}
}