<?php

require_once 'Node.php';

class Operator extends Node
{
	private $type;
	
	public function __construct( $type )
	{
		$this->type = $type;
	}

	public function compile( $compiler )
	{
		if( $this->type === '+' )
		{
			$compiler->write( ' . ' );
		}
		else
		{
			$compiler->write( ' ' . $this->type . ' ' );
		}
	}
}
