<?php

require_once 'Node.php';

class Expression extends Node
{
	public function compile()
	{
		if( $this->isAttribute() )
		{
			return $this->getCompiledChildren();
		}
		
		return '<?php echo htmlspecialchars(' . $this->getCompiledChildren() . '); ?>';
	}
}