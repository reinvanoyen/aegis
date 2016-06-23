<?php

require_once 'Node.php';
require_once 'Template/Renderer.php';

class IncludeNode extends Node
{
	public function run()
	{

	}
	
	public function compile()
	{
		return '<?php $this->render(' . $this->getCompiledAttributes() . '); ?>';
	}
}