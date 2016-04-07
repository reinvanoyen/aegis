<?php

require_once 'Node.php';

class Text extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function compile()
	{
		return $this->value;
	}
}