<?php

require_once 'Node/Expression.php';
require_once 'Node/ExtendNode.php';
require_once 'Node/IfNode.php';
require_once 'Node/TextNode.php';
require_once 'Node/BlockNode.php';
require_once 'Node/OptionNode.php';
require_once 'Node/Variable.php';
require_once 'Node/Operator.php';
require_once 'Node/StringNode.php';
require_once 'Node/Number.php';
require_once 'Node/RawNode.php';
require_once 'Node/IncludeNode.php';
require_once 'Node/LoopNode.php';
require_once 'Node/ForNode.php';

class NodeFactory
{
	public static function create( $type, $value = NULL )
	{
		switch( $type )
		{
			case Token::T_TEXT:

				return new TextNode( $value );
				break;

			case Token::T_STRING:

				return new StringNode( $value );
				break;

			case Token::T_NUMBER:

				return new Number( $value );
				break;

			case Token::T_IDENT:

				if( $value === 'extends' )
				{
					return new ExtendNode();
				}

				if( $value === 'block' )
				{
					return new BlockNode();
				}

				if( $value === 'append' || $value === 'prepend' )
				{
					return new OptionNode( $value );
				}

				if( $value === 'if' )
				{
					return new IfNode();
				}

				if( $value === 'raw' || $value === 'r' )
				{
					return new RawNode();
				}

				if( $value === 'include' )
				{
					return new IncludeNode();
				}

				if( $value === 'loop' )
				{
					return new LoopNode();
				}
			
				if( $value === 'for' )
				{
					return new ForNode();
				}

				break;

			case Token::T_VAR:
				return new Variable( $value );
				break;

			case Token::T_OP:

				return new Operator( $value );
				break;
		}

		throw new Exception( 'Couldn\'t create node for type ' . $type . ' and value ' . $value );
	}
}