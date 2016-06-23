<?php

require_once 'Node.php';

class Raw extends Node
{
	public function run()
	{

	}
	
	public function compile()
	{
		return '<?php echo ' . $this->getCompiledAttributes() . '; ?>';
	}
}