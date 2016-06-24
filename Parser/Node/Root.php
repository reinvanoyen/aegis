<?php

require_once 'Node.php';

class Root extends Node
{
	public function run()
	{
		foreach( $this->getChildren() as $c )
		{
			$c->run();
		}
	}

	public function compile( $compiler )
	{
		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}
	}
}
