<?php

abstract class Node
{
	public $parent = NULL;
	private $children = [];
	private $attributes = [];

	public function setAttribute( $k, $v )
	{
		$this->attributes[ $k ] = $v;
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

	public function removeChild( $i )
	{
		unset( $this->children[ $i ] );

	}

	public function removeLastChild()
	{
		array_pop( $this->children );
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
