<?php

abstract class Node
{
	private $token;

	public $parent = NULL;
	private $children = [];

	public function __construct( Token $token )
	{
		$this->token = $token;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function getChild( $i )
	{
		return $this->children[ $i ];
	}

	public function getLastChild()
	{
		return end( $this->children );
	}

	public function getSiblings()
	{
		return $this->parent->children;
	}

	public function appendNode( Node $node )
	{
		$this->children[] = $node;
	}

	public function getToken()
	{
		return $this->token;
	}
}
