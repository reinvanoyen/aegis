<?php

require_once 'Node.php';

class Variable extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public function compile( $compiler )
	{
		$compiler->write( '$this->' . str_replace( '.', '->', $this->name ) );
	}
}
