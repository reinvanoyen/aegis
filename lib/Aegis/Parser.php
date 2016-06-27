<?php

namespace Aegis;

use Aegis\Node;

class Parser implements ParserInterface
{
	private $root;
	private $scope;

	private $tokens;
	private $cursor;
	private $last_token_index;

	public function parse( TokenStream $stream )
	{
		$this->root = new Node\RootNode();
		$this->scope = $this->root;
		$this->cursor = 0;
		$this->tokens = $stream->getTokens();
		$this->last_token_index = count( $this->tokens ) - 1;

		$this->parseOutsideTag();

		return $this->getRoot();
	}

	public function parseOutsideTag()
	{
		$this->accept( Token::T_TEXT );

		if( $this->skip( Token::T_OPENING_TAG ) )
		{
			$this->parseStatement();
		}
	}

	private function parseStatement()
	{
		foreach( NodeRegistryNew::getNodes() as $node ) {

			$node::parse( $this );
		}

		$this->parseFor();
		$this->parseLoop();
		$this->parseRaw();
		$this->parseInclude();
		$this->parseExpression();
	}

	private function parseExpression()
	{
		if(
			$this->accept( Token::T_VAR ) ||
			$this->accept( Token::T_STRING ) ||
			$this->accept( Token::T_NUMBER )
		)
		{
			if( ! $this->scope instanceof Expression )
			{
				$this->wrap( new Node\Expression() );
			}

			if( $this->accept( Token::T_OP ) )
			{
				$this->parseExpression();
			}

			if( $this->skip( Token::T_CLOSING_TAG ) )
			{
				if( $this->scope instanceof Node\Expression )
				{
					$this->traverseDown();
				}

				$this->parseOutsideTag();
			}
		}
	}

	public function parseAttribute()
	{
		if(
			$this->accept( Token::T_VAR ) ||
			$this->accept( Token::T_STRING ) ||
			$this->accept( Token::T_NUMBER )
		)
		{
			if( ! $this->scope instanceof Node\Expression )
			{
				$this->wrap( new Node\Expression() );
			}

			if( $this->accept( Token::T_OP ) )
			{
				$this->parseAttribute();
			}
			else
			{
				if( $this->scope instanceof Node\Expression )
				{
					$this->traverseDown();
				}
				
				$this->setAttribute();
			}
		}
		else
		{
			throw new SyntaxError( 'Missing attribute for node ' . $this->scope->getName() . ', got ' . $this->getCurrentToken()->getName() );
		}
	}

	private function parseRaw()
	{
		if( $this->accept( Token::T_IDENT, 'raw' ) || $this->accept( Token::T_IDENT, 'r' ) )
		{
			$this->traverseUp();
			$this->parseAttribute();
			$this->skip( Token::T_CLOSING_TAG );
			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}

	private function parseInclude()
	{
		if( $this->accept( Token::T_IDENT, 'include' ) )
		{
			$this->traverseUp();
			$this->parseAttribute();
			$this->skip( Token::T_CLOSING_TAG );
			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}

	private function parseFor()
	{
		if( $this->accept( Token::T_IDENT, 'for' ) )
		{
			$this->traverseUp();
			$this->expect( Token::T_VAR );
			$this->setAttribute();
			
			$this->skip( Token::T_IDENT, 'in' );
			
			$this->expect( Token::T_VAR );
			$this->setAttribute();
			
			$this->skip( Token::T_CLOSING_TAG );
			
			$this->parseOutsideTag();

			$this->skip( Token::T_OPENING_TAG );
			$this->skip( Token::T_IDENT, '/for' );
			$this->skip( Token::T_CLOSING_TAG );
			
			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}
	
	private function parseLoop()
	{
		if( $this->accept( Token::T_IDENT, 'loop' ) )
		{
			$this->traverseUp();
			$this->parseAttribute();
			$this->skip( Token::T_CLOSING_TAG );

			$this->parseOutsideTag();

			$this->skip( Token::T_OPENING_TAG );
			$this->skip( Token::T_IDENT, '/loop' );
			$this->skip( Token::T_CLOSING_TAG );

			$this->traverseDown();
			$this->parseOutsideTag();
		}
	}

	public function expect( $type, $value = NULL )
	{
		if( ! $this->accept( $type, $value ) )
		{
			throw new SyntaxError( 'Expected ' . $type . ' got ' . $this->getCurrentToken() );
		}
	}

	public function skip( $type, $value = NULL )
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

	public function accept( $type, $value = NULL )
	{
		if( $this->getCurrentToken()->getType() === $type )
		{
			if( $value )
			{
				if( $this->getCurrentToken()->getValue() === $value )
				{
					//NodeRegistryNew::create( $type, $this->getCurrentToken()->getValue() );

					$this->insert( NodeRegistry::create( $type, $this->getCurrentToken()->getValue() ) );
					$this->advance();
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}

			//NodeRegistryNew::create( $type, $this->getCurrentToken()->getValue() );

			$this->insert( NodeRegistry::create( $type, $this->getCurrentToken()->getValue() ) );
			$this->advance();
			return TRUE;
		}

		return FALSE;
	}

	public function getCurrentToken()
	{
		return $this->tokens[ $this->cursor ];
	}

	public function setScope( Node $scope )
	{
		$this->scope = $scope;
	}

	public function traverseUp()
	{
		$this->scope = $this->scope->getLastChild();
	}

	public function traverseDown()
	{
		if( ! $this->scope->parent )
		{
			throw new Exception( 'Could not return from scope because scope is already on root level' );
		}

		$this->scope = $this->scope->parent;
	}

	public function advance()
	{
		if( $this->cursor < count( $this->tokens ) - 1 )
		{
			$this->cursor++;
		}
	}

	public function root()
	{
		$this->scope = $this->root;
	}

	public function wrap( Node\Node $node )
	{
		$last = $this->scope->getLastChild(); // Get the last insert node
		$this->scope->removeLastChild(); // Remove it

		$this->insert( $node );
		$this->traverseUp();
		$this->insert( $last );
	}

	public function setAttribute()
	{
		$last = $this->scope->getLastChild(); // Get the last inserted node
		$this->scope->removeLastChild(); // Remove it
		$this->scope->setAttribute( $last );
	}

	public function insert( Node\Node $node )
	{
		$node->parent = $this->scope;
		$this->scope->insert( $node );
	}

	public function getRoot()
	{
		return $this->root;
	}
}