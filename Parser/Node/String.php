<?php

require_once 'Node.php';

class String extends Node
{
	public function compile()
	{
		return 'str';
	}
}