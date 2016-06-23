<?php

require_once 'Node.php';
require_once 'Template/Template.php';

class ExtendNode extends Node
{
	public function compile( $compiler )
	{
		$compiler->head( '<?php $this->extend( ' );

		foreach( $this->getAttributes() as $c )
		{
			$subcompiler = new Compiler( $c );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( '); ?>' );
	}
}
