<?php

abstract class Node
{
	private $token;

	private $parent = NULL;
	private $children = [];

	public function __construct( Token $token )
	{
		$this->token = $token;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function getSiblings()
	{
		return $this->parent->children;
	}

	public function appendNode( Node $node )
	{
		$this->children[] = $node;
	}
}