<?php

require_once 'Node.php';

class Number extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function run()
	{
		
	}

	public function compile()
	{
		return $this->value;
	}
}