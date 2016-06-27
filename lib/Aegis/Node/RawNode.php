<?php

namespace Aegis\Node;

class RawNode extends Node
{
	public function compile( $compiler )
	{
		$compiler->write( '<?php echo ' );

		foreach( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->write( '; ?>' );
	}
}