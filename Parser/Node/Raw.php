<?php

require_once 'Node.php';

class Raw extends Node
{
	public function compile( $compiler )
	{
		$compiler->write( '<?php echo ' . $this->getCompiledAttributes() . '; ?>' );
	}
}
