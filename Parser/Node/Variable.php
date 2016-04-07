<?php

require_once 'Node.php';

class Variable extends Node
{
	public function compile()
	{
		return ' (VAR) ';
	}
}