<?php

require_once 'Node.php';

class IfNode extends Node
{
	public function compile()
	{
		return ' (if) ';
	}
}