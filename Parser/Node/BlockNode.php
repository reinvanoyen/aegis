<?php

require_once 'Node.php';
require_once 'Compiler/Compiler.php';

class BlockNode extends Node
{
	public function compile( $compiler )
	{
		$compiler->head( '<?php $this->setBlock( ' );

		foreach( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler( $a );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( ', function() { ?>' );

		foreach( $this->getChildren() as $c )
		{
			$subcompiler = new Compiler( $c );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( '<?php } ) ?>' );

		$compiler->write( '<?php $this->getBlock( ' );

		foreach( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler( $a );
			$compiler->write( $subcompiler->compile() );
		}

		$compiler->write( ') ?>' );
	}
}
