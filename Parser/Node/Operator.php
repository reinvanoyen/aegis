<?php

require_once 'Node.php';

class Operator extends Node
{
	public function compile()
	{
		return ' (OPERATOR) ';
	}
}