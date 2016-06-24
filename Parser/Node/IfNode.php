<?php

require_once 'Node.php';

class IfNode extends Node
{
	public function run()
	{
		foreach( $this->getChildren() as $c )
		{
			$c->run();
		}
	}
	
	public function compile( $compiler )
	{
		$compiler->write('<?php if( ' );

		foreach( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->write( ' ): ?>');
		
		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}

		$compiler->write( '<?php endif; ?>' );
	}
}
