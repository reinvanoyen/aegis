<?php

require_once 'Node/Expression.php';
require_once 'Node/ExtendNode.php';
require_once 'Node/IfNode.php';
require_once 'Node/Text.php';
require_once 'Node/Block.php';
require_once 'Node/Variable.php';
require_once 'Node/Operator.php';
require_once 'Node/String.php';

class NodeFactory
{
	public static function create( $type, $value = NULL )
	{
		switch( $type )
		{
			case Token::T_TEXT:

				return new Text( $value );
				break;

			case Token::T_STRING:

				return new String();
				break;

			case Token::T_IDENT:

				if( $value === 'extends' )
				{
					return new ExtendNode();
				}

				if( $value === 'block' )
				{
					return new Block();
				}

				if( $value === 'if' )
				{
					return new IfNode();
				}

				break;

			case Token::T_VAR:

				return new Variable();
				break;

			case Token::T_OP:

				return new Operator();
				break;
		}

		throw new Exception( 'Couldn\'t create node for type ' . $type . ' and value ' . $value );
	}
}