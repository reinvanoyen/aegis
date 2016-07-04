<?php

namespace Aegis;

use Aegis\Node;

class Parser implements ParserInterface
{
	private $root;
	private $scope;

	private $tokens;
	private $cursor;
	private $lastTokenIndex;

	public function parse( TokenStream $stream )
	{
		$this->root = new Node\RootNode();
		$this->scope = $this->root;
		$this->cursor = 0;
		$this->tokens = $stream->getTokens();
		$this->lastTokenIndex = count( $this->tokens ) - 1;

		$this->parseOutsideTag();

		return $this->getRoot();
	}

	public function parseOutsideTag()
	{
		if( ! count( $this->tokens ) ) {

			return;
		}

		if( $this->accept( Token::T_TEXT ) ) {

			$this->insert( new Node\TextNode( $this->getCurrentToken()->getValue() ) );
			$this->advance();
		}

		if( $this->skip( Token::T_OPENING_TAG ) ) {

			$this->parseStatement();
		}
	}

	private function parseStatement()
	{
		foreach( NodeRegistry::getNodes() as $node ) {

			$node::parse( $this );
		}
	}

	public function expect( $type, $value = NULL )
	{
		if( ! $this->accept( $type, $value ) ) {

			$this->syntaxError( $type, $value );
		}

		return TRUE;
	}

	public function expectNext( $type, $value = NULL )
	{
		if( ! $this->acceptNext( $type, $value ) ) {

			$this->syntaxError( $type, $value );
		}

		return TRUE;
	}
	
	public function skip( $type, $value = NULL )
	{
		if( $this->accept( $type, $value ) ) {

			$this->advance();
			return TRUE;
		}

		return FALSE;
	}

	public function accept( $type, $value = NULL )
	{
		if( $this->getCurrentToken()->getType() === $type ) {

			if( $value ) {

				if( $this->getCurrentToken()->getValue() === $value ) {

					return TRUE;
				}

				return FALSE;
			}
			
			return TRUE;
		}

		return FALSE;
	}

	public function acceptNext( $type, $value = NULL )
	{
		if( $this->getNextToken()->getType() === $type ) {

			if( $value ) {

				if( $this->getNextToken()->getValue() === $value ) {

					return TRUE;
				}

				return FALSE;
			}

			return TRUE;
		}

		return FALSE;
	}

	public function syntaxError( $type, $value )
	{
		throw new SyntaxError( 'Expected ' . strtoupper( $type  . ' ' . $value ) . ' got ' . $this->getCurrentToken() . ' on line ' . $this->getCurrentToken()->getLine() );
	}

	public function getCurrentToken()
	{
		return $this->tokens[ $this->cursor ];
	}

	public function getNextToken()
	{
		return $this->tokens[ $this->cursor + 1 ];
	}

	public function setScope( \Aegis\Node $scope )
	{
		$this->scope = $scope;
	}

	public function getScope()
	{
		return $this->scope;
	}

	public function getRoot()
	{
		return $this->root;
	}

	public function traverseUp()
	{
		$this->scope = $this->scope->getLastChild();
	}

	public function traverseDown()
	{
		if( ! $this->scope->parent ) {

			throw new Exception( 'Could not return from scope because scope is already on root level' );
		}

		$this->scope = $this->scope->parent;
	}

	public function advance()
	{
		if( $this->cursor < count( $this->tokens ) - 1 ) {

			$this->cursor++;
		}
	}

	public function root()
	{
		$this->scope = $this->root;
	}

	public function wrap( \Aegis\Node $node )
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

	public function insert( \Aegis\Node $node )
	{
		$node->parent = $this->scope;
		$this->scope->insert( $node );
	}
}