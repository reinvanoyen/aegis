<?php

require_once 'Node.php';

class Block extends Node
{
	public function compile()
	{
		return $this->getCompiledChildren();
	}
}