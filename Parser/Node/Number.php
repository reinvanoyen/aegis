<?php

require_once 'Node.php';

class Number extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function compile( $compiler )
	{
		$compiler->write( $this->value );
	}
}
