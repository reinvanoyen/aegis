<?php

namespace Aegis;

use Aegis\Node;

class NodeRegistry
{
	public static function register( $classname )
	{
		// @TODO
	}

	public static function create( $type, $value = NULL )
	{
		switch( $type )
		{
			case Token::T_TEXT:

				return new Node\TextNode( $value );
				break;

			case Token::T_STRING:

				return new Node\StringNode( $value );
				break;

			case Token::T_NUMBER:

				return new Node\Number( $value );
				break;

			case Token::T_IDENT:

				if( $value === 'extends' )
				{
					return new Node\ExtendNode();
				}

				if( $value === 'block' )
				{
					return new Node\BlockNode();
				}

				if( $value === 'append' || $value === 'prepend' )
				{
					return new Node\OptionNode( $value );
				}

				if( $value === 'if' )
				{
					return new Node\IfNode();
				}

				if( $value === 'raw' || $value === 'r' )
				{
					return new Node\RawNode();
				}

				if( $value === 'include' )
				{
					return new Node\IncludeNode();
				}

				if( $value === 'loop' )
				{
					return new Node\LoopNode();
				}
			
				if( $value === 'for' )
				{
					return new Node\ForNode();
				}

				break;

			case Token::T_VAR:
				return new Node\VariableNode( $value );
				break;

			case Token::T_OP:

				return new Node\Operator( $value );
				break;
		}

		throw new Exception( 'Couldn\'t create node for type ' . $type . ' and value ' . $value );
	}
}