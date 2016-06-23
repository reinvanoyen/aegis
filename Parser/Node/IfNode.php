<?php

require_once 'Node.php';

class IfNode extends Node
{
	public function run()
	{
		foreach( $this->getChildren() as $c )
		{
			$c->run();
		}
	}
	
	public function compile()
	{
		$output = '<?php if( ' . $this->getCompiledAttributes() . ' ): ?>';

		$output .= $this->getCompiledChildren();

		$output .= '<?php endif; ?>';

		return $output;
	}
}