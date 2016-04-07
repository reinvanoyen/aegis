<?php

require_once 'Node.php';

class ExtendNode extends Node
{
	public function compile()
	{
		return '<?php $this->extends(' . $this->getChild( 0 )->compile() . '); ?>';
	}
}
