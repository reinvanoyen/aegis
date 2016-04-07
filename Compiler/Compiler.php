<?php

class Compiler
{
	public function compile( Node $node )
	{
		return $node->compile();
	}
}