<?php

require_once 'Node.php';

class Raw extends Node
{
	public function compile()
	{
		return '<?php echo ' . $this->getCompiledAttributes() . '; ?>';
	}
}