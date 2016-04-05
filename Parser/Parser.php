<?php

require_once 'ParserInterface.php';
require_once 'SyntaxError.php';
require_once 'Node/Root.php';
require_once 'Node/Expression.php';
require_once 'Node/ExtendNode.php';
require_once 'Node/Text.php';
require_once 'Node/Block.php';

class Parser implements ParserInterface
{
	private $root;
	private $scope;

	private $tokens;
	private $cursor;
	private $last_token_index;

	public function parse( TokenStream $stream )
	{
		$this->root = new Root();
		$this->scope = $this->root;
		$this->cursor = 0;
		$this->tokens = $stream->getTokens();
		$this->last_token_index = count( $this->tokens ) - 1;

		$this->parseOutsideTag();

		return $this->getRoot();
	}

	private function parseOutsideTag()
	{
		$this->accept( Token::T_TEXT );

		if( $this->skip( Token::T_OPENING_TAG ) )
		{
			$this->parseStatement();
		}
	}

	private function parseStatement()
	{
		$this->parseExtends();
		$this->parseBlock();
		$this->parseIf();
		$this->parseRaw();
		$this->parseExpression();
	}

	private function parseExpression()
	{
		// @TODO make nodes from expressions

		if( $this->accept( Token::T_VAR ) || $this->accept( Token::T_STRING ) )
		{
			if( $this->accept( Token::T_OP ) )
			{
				$this->parseExpression();
			}

			if( $this->skip( Token::T_CLOSING_TAG ) )
			{
				$this->parseOutsideTag();
			}
		}
	}

	private function parseRaw()
	{
		if( $this->accept( Token::T_IDENT, 'raw' ) )
		{
			$this->traverseUp();
			$this->accept( Token::T_STRING );
			$this->accept( Token::T_IDENT );

			if( $this->skip( Token::T_CLOSING_TAG ) )
			{
				$this->traverseDown();
				$this->parseOutsideTag();
			}
		}
	}

	private function parseExtends()
	{
		if( $this->accept( Token::T_IDENT, 'extends' ) )
		{
			$this->traverseUp();
			$this->expect( Token::T_STRING );

			if( $this->skip( Token::T_CLOSING_TAG ) )
			{
				$this->traverseDown();
				$this->parseOutsideTag();
			}
		}
	}

	private function parseBlock()
	{
		if( $this->accept( Token::T_IDENT, 'block' ) )
		{
			$this->traverseUp();

			$this->expect( Token::T_STRING );
			$this->skip( Token::T_CLOSING_TAG );

			$this->accept( Token::T_TEXT );

			$this->skip( Token::T_OPENING_TAG );
			$this->skip( Token::T_IDENT, 'block' );
			$this->skip( Token::T_CLOSING_TAG );

			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}

	private function parseIf()
	{
		if( $this->accept( Token::T_IDENT, 'if' ) )
		{
			$this->traverseUp();

			$this->expect( Token::T_STRING );
			$this->skip( Token::T_CLOSING_TAG );

			$this->accept( Token::T_TEXT );

			$this->skip( Token::T_OPENING_TAG );
			$this->skip( Token::T_IDENT, 'if' );
			$this->skip( Token::T_CLOSING_TAG );

			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}

	private function expect( $type, $value = NULL )
	{
		if( ! $this->accept( $type, $value ) )
		{
			throw new SyntaxError( 'Expected ' . $type . ' got ' . $this->getCurrentToken()->getType() );
		}
	}

	private function skip( $type, $value = NULL )
	{
		if( $this->getCurrentToken()->getType() === $type )
		{
			if( $value )
			{
				if( $this->getCurrentToken()->getValue() === $value )
				{
					$this->advance();
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			
			$this->advance();
			return TRUE;
		}

		return FALSE;
	}

	private function accept( $type, $value = NULL )
	{
		if( $this->getCurrentToken()->getType() === $type )
		{
			if( $value )
			{
				if( $this->getCurrentToken()->getValue() === $value )
				{
					$this->appendNode( new Text( $this->getCurrentToken() ) );
					$this->advance();
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			
			$this->appendNode( new Text( $this->getCurrentToken() ) );
			$this->advance();
			return TRUE;
		}

		return FALSE;
	}

	private function getCurrentToken()
	{
		return $this->tokens[ $this->cursor ];
	}

	private function setScope( Node $scope )
	{
		$this->scope = $scope;
	}

	private function traverseUp()
	{
		$this->scope = $this->scope->getLastChild();
	}

	private function traverseDown()
	{
		if( ! $this->scope->parent )
		{
			throw new Exception( 'Could not return from scope because scope is already on root level' );
		}

		$this->scope = $this->scope->parent;
	}

	private function advance()
	{
		if( $this->cursor < count( $this->tokens ) - 1 )
		{
			$this->cursor++;
		}
	}

	private function root()
	{
		$this->scope = $this->root;
	}

	private function appendNode( Node $node )
	{
		$node->parent = $this->scope;
		$this->scope->appendNode( $node );
	}

	public function getRoot()
	{
		return $this->root;
	}
}
