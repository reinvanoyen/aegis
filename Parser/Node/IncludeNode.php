<?php

require_once 'Node.php';
require_once 'Template.php';

class IncludeNode extends Node
{
	public function compile()
	{
		return '<?php $this->render(' . $this->getCompiledAttributes() . '); ?>';
	}
}