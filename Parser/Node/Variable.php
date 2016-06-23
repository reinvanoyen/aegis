<?php

require_once 'Node.php';

class Variable extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public function run() {}

	public function compile()
	{
		return '$this->' . str_replace( '.', '->', $this->name );
	}
}