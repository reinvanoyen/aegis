<?php

require_once 'Node.php';

class RootNode extends Node
{
	public function compile( $compiler )
	{
		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}
	}
}
