<?php

require_once 'Node.php';

class Expression extends Node
{
	public function compile( $compiler )
	{
		if( $this->isAttribute() )
		{
			foreach( $this->getChildren() as $c )
			{
				$c->compile( $compiler );
			}
		}
		else
		{
			$compiler->write( '<?php echo htmlspecialchars(' );

			foreach( $this->getChildren() as $c )
			{
				$c->compile( $compiler );
			}

			$compiler->write( '); ?>' );
		}
	}
}
