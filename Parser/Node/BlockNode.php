<?php

require_once 'Node.php';
require_once 'Compiler/Compiler.php';

class BlockNode extends Node
{
	public function compile( $compiler )
	{
		$nameAttr = $this->getAttribute( 0 );
		$subcompiler = new Compiler( $nameAttr );
		$name = $subcompiler->compile();

		$blockHeadFunction = 'setBlock';

		if( $this->getAttribute( 1 ) )
		{
			$optionAttr = $this->getAttribute( 1 );

			if( $optionAttr->value === 'prepend' )
			{
				$blockHeadFunction = 'prependBlock';
			}
			else if( $optionAttr->value === 'append' )
			{
				$blockHeadFunction = 'appendBlock';
			}
		}

		$compiler->head( '<?php $this->' . $blockHeadFunction . '( ' );
		$compiler->head( $name );
		$compiler->head( ', function() { ?>' );

		foreach( $this->getChildren() as $c )
		{
			$subcompiler = new Compiler( $c );
			$compiler->head( $subcompiler->compile() );
		}

		$compiler->head( '<?php } ) ?>' );

		$compiler->write( '<?php $this->getBlock( ' );
		$compiler->write( $name );
		$compiler->write( ') ?>' );
	}
}