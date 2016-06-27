<?php

namespace Aegis\Node;

class IncludeNode extends Node
{
	public function compile( $compiler )
	{
		$compiler->write( '<?php $this->render(' );
		
		foreach( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->write( '); ?>' );
	}
}
