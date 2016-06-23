<?php

require_once 'Node.php';

class LoopNode extends Node
{
	public function compile( $compiler )
	{
		$compiler->write( '<?php call_user_func( function() { ?>' );
		$compiler->write( '<?php for( $i = 0; $i < ' );

		foreach( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->write( '; $i++ ): ?>' );

		foreach( $this->getChildren() as $c )
		{
			$c->compile( $compiler );
		}

		$compiler->write( '<?php endfor; ?>' );
		$compiler->write( '<?php } ); ?>' );
	}
}
