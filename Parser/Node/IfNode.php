<?php

require_once 'Node.php';

class IfNode extends Node
{
	public function compile()
	{
		$output = '<?php if( ' . $this->getChild( 0 )->compile() . ' ): ?>';

		$i = 0;
		foreach( $this->getChildren() as $c )
		{
			if( $i !== 0 )
			{
				$output .= $c->compile();
			}
			$i++;
		}

		$output .= '<?php endif; ?>';

		return $output;
	}
}
