<?php

require_once 'Node.php';

class Operator extends Node
{
	private $type;
	
	public function __construct( $type )
	{
		$this->type = $type;
	}

	public function compile()
	{
		if( $this->type === '+' )
		{
			return '.';
		}
		return $this->type;
	}
}
