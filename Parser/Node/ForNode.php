<?php

require_once 'Node.php';
require_once 'Compiler/Compiler.php';

class ForNode extends Node
{
	public function compile( $compiler )
	{
		$loopitem = $this->getAttribute( 0 );
		$arrayable = $this->getAttribute( 1 );
		
		$compiler->write( '<?php foreach(' );
		$arrayable->compile( $compiler );
		$compiler->write( ' as ' );
		$loopitem->compile( $compiler ); // @TODO this variable possibly overrides another and is globally avialable in the template, attempt to fix this!
		$compiler->write( '): ?>' );
		
		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}
		
		$compiler->write( '<?php endforeach; ?>' );
	}
}