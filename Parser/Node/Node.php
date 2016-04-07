<?php

abstract class Node
{
	public $parent = NULL;
	private $children = [];

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

	public function removeChild( $i )
	{
		unset( $this->children[ $i ] );
	}

	public function removeLastChild()
	{
		$this->removeChild( count( $this->children ) - 1 );
	}

	public function getSiblings()
	{
		return $this->parent->children;
	}

	public function insert( Node $node )
	{
		$this->children[] = $node;
	}

	abstract public function compile();
}
