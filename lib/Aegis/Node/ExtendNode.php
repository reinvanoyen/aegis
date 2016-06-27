<?php

namespace Aegis\Node;

use Aegis\Compiler;

class ExtendNode extends Node
{
	public function compile( $compiler )
	{
		// Render the head of the extended template

		$compiler->head( '<?php $this->renderHead( ' );

		foreach( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler( $a );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( '); ?>' );

		// Write the head of the current template

		foreach( $this->getChildren() as $c )
		{
			$subcompiler = new Compiler( $c );
			$subcompiler->compile();
			$compiler->head( $subcompiler->getHead() );
		}

		// Render the body of the extended template

		$compiler->write( '<?php $this->renderBody( ' );

		foreach( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler( $a );
			$compiler->write( $subcompiler->compile() );
		}

		$compiler->write( '); ?>' );
	}
}