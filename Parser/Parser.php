<?php

require_once 'ParserInterface.php';
require_once 'Node/Text.php';
require_once 'Node/Block.php';

class Parser implements ParserInterface
{
	private $nodes = [];

	private $scope;

	public function parse( TokenStream $stream )
	{
		$tokens = $stream->getTokens();

		foreach( $tokens as $token )
		{
			if( $token->getType() === Token::T_TEXT )
			{
				$this->nodes[] = new Text( $token );
			}

			if( $token->getType() === Token::T_OPENING_TAG )
			{
			}
		}
	}

	private function setScope( Node $scope )
	{
		$this->scope = $scope;
	}

	private function returnFromScope()
	{
		if( ! $this->scope->parent )
		{
			throw new Exception( 'Could not return from scope because scope is already on root level' );
		}

		$this->scope = $this->scope->parent;
	}

	private function appendNode( Node $node )
	{
		$this->scope->appendNode( $node );
	}
}