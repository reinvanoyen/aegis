<?php

require_once 'Node.php';

class Expression extends Node
{
	public function __construct()
	{
	}

	public function compile()
	{
		$output = '';

		foreach( $this->getChildren() as $c )
		{
			$output .= $c->compile();
		}

		return $output;
	}
}